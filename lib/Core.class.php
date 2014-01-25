<?php

class Core
{
    private $socket;
    private $config = array();
    private $input = array();
    private $triggers = array();
    
    public function __construct(array $config) 
    {
        $this->config = $config;
    }
    
    public function run()
    {
        $this->connect();
        $exit = false;
        while (!$exit)
        {
            $this->getEvent();
            $exit = $this->processTriggers();
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
        if (count($this->input) == 0)
            return;
        echo ">> ".$data;
    }
    
    private function processTriggers()
    {
        if (count($this->input) == 0)
            return false;
        if ($this->input[0] == 'PING')
        {
            $resp = "PONG ".  $this->input[1];
            $this->send($resp);
        }
        if (count ($this->input) >= 5 && $this->input[4]=="Found")
        {
            $nick = $this->config['nick'];
            $mess = "USER $nick agrume.pl $nick :$nick";
            $this->send($mess);
            $this->send("NICK $nick");
        }
        return false;
    }
    
    private function send($message)
    {
        fputs($this->socket, $message."\n");
        echo "<< ".$message."\n";
    }
}