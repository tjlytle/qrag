<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initCloudmine()
    {
        require_once 'CloudMine.php';
        $config = $this->getOption('cloudmine');
        $cloudmine = new CloudMine($config['id'], $config['key']);
        Zend_Registry::set('cloudmine', $cloudmine);
        return $cloudmine;
    }
    
    protected function _initGame()
    {
        $cloudmine = $this->getResource('cloudmine');
        $game = new Qrag_Service_Game($cloudmine);
        Zend_Registry::set('game', $game);
        return $game;
    }
}

