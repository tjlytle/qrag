<?php
class Qrag_Model_Game
{
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETE = 'complete';
    
    protected $id;
    protected $players = array();
    protected $status = self::STATUS_ACTIVE;
    
    public function __construct($status = self::STATUS_ACTIVE, $players = array(), $id = null)
    {
        if(is_null($id)){
            $id = uniqid();
        }
        
        $this->id = $id;
        $this->status = $status;
        $this->players = $players;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getCode($player)
    {
        return $this->getId() . '.' . $player->getId();
    }
    
    public function addPlayer(Qrag_Model_Player $player)
    {
        $this->players[$player->getId()] = $player;
    }
    
    public function getPlayerByNumber($number)
    {
        foreach($this->players as $player){
            if($player->getNumber() == $number){
                return $player;
            }
        }
    }
    
    public function getPlayerById($id)
    {
        foreach($this->players as $player){
            if($player->getId() == $id){
                return $player;
            }
        }
    }
    
    public function getPlayers()
    {
        return $this->players;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function jsonSerialize()
    {
        $players = array();
        foreach($this->players as $player){
            $players[$player->getNumber()] = $player->getId();
        }
        
        return array(
            'status' => $this->status,
            'players' => $players,
        );
    }
}