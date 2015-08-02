<?php

namespace Alyosha\IRC\Event\Command;

class InviteCommand extends AbstractIrcCommand
{
    const COMMAND = 'INVITE %s %s';

    protected $target;
    protected $channel;

    public function __construct($server, $channel, $target)
    {
        $this->server = $server;
        $this->channel = $channel;
        $this->target = $target;
    }

    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->target, $this->channel);
    }

    public function getCommandName()
    {
        return 'invite';
    }
} 