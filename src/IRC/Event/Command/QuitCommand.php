<?php

namespace Alyosha\IRC\Event\Command;

class QuitCommand extends AbstractIrcCommand
{
    const COMMAND = 'QUIT :%s';

    protected $channel;
    protected $message;

    public function __construct($server, $message = '')
    {
        $this->server = $server;
        $this->message = $message;
    }
    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->message);
    }

    public function getCommandName()
    {
        return 'quit';
    }
}