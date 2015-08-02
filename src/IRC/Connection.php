<?php

namespace Alyosha\IRC;

class Connection
{
    protected $server;
    protected $port;
    protected $socket;

    public function __construct($server, $port)
    {
        $this->server = $server;
        $this->port = $port;
        $this->socket = fsockopen($server, $port);
        socket_set_blocking($this->socket, 0);
    }

    public function receive()
    {
        $input = fgets($this->socket);
        if (!empty($input)) {
            echo ">> ".$input;
            return $input;
        }
        return '';
    }

    public function send($message)
    {
        echo "<< ".$message."\n";
        fputs($this->socket, $message."\r\n");
    }
} 