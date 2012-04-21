<?php

class IndexController extends Zend_Controller_Action
{    
    /**
     * @var Qrag_Service_Game
     */
    protected $service;
    
    public function init()
    {
        $this->service = Zend_Registry::get('game');
    }

    public function indexAction()
    {
        //look for post
        if($this->getRequest()->isPost()){
            //get teams
            $team['a'] = $this->_getParam('a');
            $team['b'] = $this->_getParam('b');
            
            if(empty($team['a']) OR empty($team['b'])){
                throw new Exception('both teams must have players');
            }
            
            //setup data
            $game = new Qrag_Model_Game();

            foreach(array('a', 'b') as $t){
                for($i = 0; $i < count($team[$t]['number']); $i++){
                    $player = new Qrag_Model_Player($team[$t]['number'][$i], $team[$t]['name'][$i], $t);
                    $game->addPlayer($player);
                }
            }
            
            //save some data
            $this->service->storeGame($game);
            
            //redirect to qr page
            $this->getHelper('redirector')->gotoSimple('qrcode', 'index', null, array('id' => $game->getId()));
        }
    }

    public function qrcodeAction()
    {
        // action body
        $game = $this->service->getGameById($this->_getParam('id'));
        $this->view->game = $game;
    }

    public function boardAction()
    {
        // action body
    }


}





