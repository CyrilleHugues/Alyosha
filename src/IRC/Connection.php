<?php

namespace Alyosha\IRC;

class Connection
{
    /**
     * @var string
     */
    protected $server;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var resource
     */
    protected $socket;

    /**
     * @param string $server
     * @param string $port
     */
    public function __construct($server, $port)
    {
        $this->server = $server;
        $this->port = $port;
        $this->socket = fsockopen($server, $port);
        socket_set_blocking($this->socket, 0);
    }

    /**
     * @return string
     */
    public function receive()
    {
        $input = fgets($this->socket);
        if (!empty($input)) {
            echo ">> ".$input;
            return $input;
        }
        return '';
    }

    /**
     * @param $message
     */
    public function send($message)
    {
        echo "<< ".$message."\n";
        fputs($this->socket, $message."\r\n");
    }
}
