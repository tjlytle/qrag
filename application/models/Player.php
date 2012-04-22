<?php
class Qrag_Model_Player
{
    const STATUS_ACTIVE = 'active';
    const STATUS_KILLED = 'killed';
    
    const TEAM_A = 'a';
    const TEAM_B = 'b';
    
    protected $id;
    protected $number;
    protected $name;
    protected $team;
    protected $kills;
    protected $status;
    
    public function __construct($number, $name, $team, $status = self::STATUS_ACTIVE, $kills = array(), $id = null)
    {        
        if(is_null($id)){
            $id = uniqid();
        }
        
        $this->id = $id;
        $this->number = $number;
        $this->name = $name;
        
        if(!in_array($team, array(self::TEAM_A, self::TEAM_B))){
            throw new Exception('invalid team');
        }
        
        $this->team = $team;
        $this->kills = $kills;
        
        $this->setStatus($status);
    }
    
    public function kill(Qrag_Model_Player $player){
        if($player->getTeam() == $this->getTeam()){
            throw new Exception('No firendly fire.');
        }
        
        if($player->getStatus() != self::STATUS_ACTIVE){
            throw new Exception('Player already killed.');
        }
        
        if($this->getStatus() != self::STATUS_ACTIVE){
            throw new Exception("You're not longer active.");
        }
        
        $player->setStatus(self::STATUS_KILLED);
        $this->kills[] = $player->getId();
    }
    
    public function setStatus($status)
    {
        if(!in_array($status, array(self::STATUS_ACTIVE, self::STATUS_KILLED))){
            throw new Exception('invalid status');
        }
        
        $this->status = $status;        
    }
    
    public function getTeam()
    {
        return $this->team;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getId()
    {
        return $this->id;
    }
        
    public function getName()
    {
        return $this->name;
    }
    
    public function getNumber()
    {
        return $this->number;
    }
    
    public function jsonSerialize()
    {
        return array(
            'type' => 'player',
            'number' => $this->number,
            'team' => $this->team,
            'status' => $this->status,
            'name' => $this->name,
            'kills' => $this->kills
        );
    }
}