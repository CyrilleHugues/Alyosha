<?php

class Core
{
    private $socket;
    private $config = array();
    private $events = array();
    private $triggers = array();
    
    public function __construct(array $config) 
    {
        $this->config = $config;
    }
    
    public function run()
    {
        $this->socket = fsockopen(
                $this->config['server'],
                $this->config['port']
        );
        $exit = false;
        while (!$exit)
        {
            $this->getEvents();
            $exit = $this->processTriggers();
        }
    }
    
    private function getEvents()
    {
    }
    
    private function processTriggers()
    {
    }
}