<?php

namespace Alyosha\IRC\Event\Command;

class KickCommand extends AbstractIrcCommand
{
    const COMMAND = 'KICK %s %s %s';

    protected $channel;
    protected $target;
    protected $message;

    public function __construct($server, $channel, $target, $message = '')
    {
        $this->server = $server;
        $this->channel = $channel;
        $this->target = $target;
        $this->message = $message;
    }

    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->channel, $this->target, $this->message);
    }

    public function getCommandName()
    {
        return 'kick';
    }
}