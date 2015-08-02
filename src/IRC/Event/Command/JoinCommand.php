<?php

namespace Alyosha\IRC\Event\Command;

class JoinCommand extends AbstractIrcCommand
{
    const COMMAND = 'JOIN %s';

    protected $channel;

    public function __construct($server, $channel)
    {
        $this->server = $server;
        $this->channel = $channel;
    }

    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->channel);
    }

    public function getCommandName()
    {
        return 'join';
    }
}
