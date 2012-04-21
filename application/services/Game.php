<?php
class Qrag_Service_Game
{
    /**
     * @var CloudMine
     */
    protected $cloudmine;
    
    public function __construct($cloudmine)
    {
        $this->cloudmine = $cloudmine;
    }
    
    public function getGameById($id)
    {
        $data = $this->cloudmine->get('game-' . $id);
        return $this->hydrate($data['game-' .$id], $id);
    }
    
    public function storeGame(Qrag_Model_Game $game)
    {
        $data = array();
        $data['game-' . $game->getId()] = $game->jsonSerialize();
        foreach($game->getPlayers() as $player){
            $data['player-' . $player->getId()] = $player->jsonSerialize();
        }
        
        $this->cloudmine->set($data);
    }
    
    protected function hydrate($data, $id)
    {
        $keys = array();
        foreach($data['players'] as $key){
            $keys[] = 'player-' . $key;
        }        
        
        $playerdata = $this->cloudmine->get($keys);

        $players = array();
        foreach($playerdata as $playerid => $player){
            $playerid = str_replace('player-', '', $playerid);
            $players[] = new Qrag_Model_Player($player['number'], $player['name'], $player['team'], $player['status'], $playerid);
        }
        
        $game = new Qrag_Model_Game($data['status'], $players, $data['kills'], $id);
        
        return $game;
    }
}