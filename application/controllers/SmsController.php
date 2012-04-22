<?php

class SmsController extends Zend_Controller_Action
{
    /**
     * @var Qrag_Service_Game
     */
    protected $service;
    
    protected $nexmo;
    
    /**
     * @var Pubnub
     */
    protected $pubnub;
    
    public function init()
    {
        $this->service = Zend_Registry::get('game');
        $this->nexmo = Zend_Registry::get('nexmo');
        $this->pubnub = Zend_Registry::get('pubnub');
        
        $this->getHelper('viewRenderer')->setNoRender();
        $this->getHelper('layout')->disableLayout(true);
    }

    public function indexAction()
    {
        if(!$this->_hasParam('text')){
            return;
        }

        //grab all the data
        list($gameId, $victimId) = explode('.', $this->_getParam('text'));
        $game = $this->service->getGameById($gameId);
        $killer = $game->getPlayerByNumber($this->_getParam('msisdn'));
        $victim = $game->getPlayerById($victimId);
        
        $nexmo = new NexmoMessage($this->nexmo['key'], $this->nexmo['secret']);

        //try to kill
        try{
            $killer->kill($victim);
        } catch(Exception $e) {
            $nexmo->sendText($killer->getNumber(), $this->nexmo['number'], $e->getMessage());
            return;
        }
        
        //save state
        //$this->service->storeGame($game);
        
        //send with pubnub
        $this->pubnub->publish(array('channel' => 'qrag-' . $game->getId(), 'message' => array(
            'type' => 'kill',
            'killer' => $killer->jsonSerialize(),
            'victim' => $victim->jsonSerialize()
        )));

        //notify via sms
        $nexmo->sendText($killer->getNumber(), $this->nexmo['number'], "You killed " . $victim->getName());
        sleep(2);
        $nexmo->sendText($victim->getNumber(), $this->nexmo['number'], "You were killed by " . $killer->getName());
    }


}

