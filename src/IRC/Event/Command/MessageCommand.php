<?php

namespace Alyosha\IRC\Event\Command;

class MessageCommand extends AbstractIrcCommand
{
    const COMMAND = 'PRIVMSG %s :%s';

    protected $channel;
    protected $message;

    public function __construct($server, $channel, $message)
    {
        $this->server = $server;
        $this->channel = $channel;
        $this->message = $message;
    }

    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->channel, trim($this->message));
    }

    public function getCommandName()
    {
        return 'message';
    }
}
