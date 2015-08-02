<?php

namespace Alyosha\IRC\Event\Command;

class PartCommand extends AbstractIrcCommand
{
    const COMMAND = 'PART %s %s';

    protected $channel;
    protected $message;

    public function __construct($server, $channel, $message = '')
    {
        $this->server = $server;
        $this->channel = $channel;
        $this->message = $message;
    }
    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->channel, $this->message);
    }

    public function getCommandName()
    {
        return 'part';
    }
}