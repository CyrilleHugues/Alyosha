<?php

class Alyosha
{
    private $socket;
    private $config = array();
    private $input = array();
    private $events = array();
    private $triggers = array();

    public function __construct() 
    {
        $this->config = Config::$cfg;
        foreach ($config['plugins'] as $plugin)
        {
            $tools = Classes\Plugin::init($plugin);
            $names = array();
            foreach ($tools[0] as $event) 
            {
                if (in_array($event->getName(), $names))
                {
                    echo "Doublon $event->getName()";
                    exit();
                }
                array_push($this->events, $event);
                $this->triggers[$event->getName()] = array();
            }
            
            foreach ($tools[1] as $trigger)
            {
                $events = $trigger->getEvents();
                foreach ($events as $event) {
                    if (array_key_exists($event, $this->triggers))
                    {
                        array_push($this->triggers[$event], $trigger);
                    }
                }
            }
        }
    }
    
    public function run()
    {
        $this->connect();
        $exit = false;
        while (!$exit)
        {
            $events = $this->getEvent();
            $exit = $this->processTriggers($events);
        }
    }
    
    private function connect()
    {
        $this->socket = fsockopen(
                $this->config['server'],
                $this->config['port']
        );
    }

    private function getEvent()
    {
        $data = fgets($this->socket,128);
        $this->input = explode(' ', $data);
        
        $events = array();
        if (count($this->input) <= 1)
            return $events;
        echo ">> ".$data;
        foreach ($this->events as $e)
        {
            if ($e->isHappening($this->input))
            {
                array_push($events, $e->getName());
            }
        }
        return $events;
    }
    
    private function processTriggers(array $events)
    {
        $sign = false;
        
        if (count($events) == 0)
            return $sign;
        
        foreach ($events as $e)
        {
            $listTriggers = $this->triggers[$e];
            foreach ($listTriggers as $trig) 
            {
                $msgs = $trig->process($e, $this->input);
                $this->send($msgs);
                if ($msgs[0][0] == 'QUIT')
                {
                    $sign = true;
                }
            }
        }
        return $sign;
    }
    
    private function send(array $messages)
    {
        foreach ($messages as $message)
        {
            fputs($this->socket, $message[0]." ".$message[1]."\n");
            echo "<< ".$message[0]." ".$message[1]."\n";
        }
    }
}