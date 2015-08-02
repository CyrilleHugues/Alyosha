<?php

namespace Alyosha\IRC\Event\Command;

class JoinCommand extends AbstractIrcCommand
{
    const COMMAND = 'JOIN %s';

    /**
     * @var string
     */
    protected $channel;

    /**
     * @param string $server
     * @param string $channel
     */
    public function __construct($server, $channel)
    {
        $this->server = $server;
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->channel);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'join';
    }
}
