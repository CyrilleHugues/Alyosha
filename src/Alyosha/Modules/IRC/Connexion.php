<?php

namespace Alyosha\Modules\IRC;

use Alyosha\Core\Config;

class Connexion
{
	private $socket;
	private $server;
	private $port;
	private static $connexion;

	public function __construct($server, $port = 6667)
	{
        $this->server = $server;
        $this->port = $port;
		$this->socket = null;
	}

	public function __destruct()
	{
		if ($this->socket !== null) {
			fclose($this->socket);
		}
	}

	public function fire()
	{
        $this->socket = fsockopen($this->server, $this->port);
        socket_set_blocking($this->socket, 0);
	}

	public function receive()
    {
        if ($this->socket == null) {
            throw new \Exception("La socket n'est pas ouverte.'");
        }
        $input = fgets($this->socket);
        return $input;
    }
    
    public function send(array $messages)
    {
        if ($this->socket == null) {
            throw new \Exception("La socket n'est pas ouverte.'");
        }
        foreach ($messages as $message) {
            fputs($this->socket, $message."\r\n");
        }
    }
}
