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
    protected $status;
    
    public function __construct($number, $name, $team, $status = self::STATUS_ACTIVE, $id = null)
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
        
        if(!in_array($status, array(self::STATUS_ACTIVE, self::STATUS_ACTIVE))){
            throw new Exception('invalid status');
        }
        
        $this->status = $status;
    }
    
    public function kill()
    {
        $this->status = self::STATUS_ACTIVE;
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
            'name' => $this->name
        );
    }
}