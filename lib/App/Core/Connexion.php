<?php

namespace App\Core;

use Exception;

class Connexion {
    private $socket;
    
    public function __construct($server, $port) 
    {
        $this->socket = fsockopen($server, $port);
    }
    
    public function __destruct() {
        if ($this->socket !== null)
        {
            fclose($this->socket);
        }
    }
    
    public function receive()
    {
        if ($this->socket == null)
        {
            throw new Exception("La socket n'est pas ouverte.'");
        }
        return explode(' ', fgets($this->socket));
    }
    
    public function send(array $messages)
    {
        if ($this->socket == null)
        {
            throw new Exception("La socket n'est pas ouverte.'");
        }
        foreach ($messages as $message)
        {
            fputs($this->socket, $message[0]." ".$message[1]."\n");
        }
    }
}
