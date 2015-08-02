<?php

namespace Alyosha\IRC\Event\Command;

class KickCommand extends AbstractIrcCommand
{
    const COMMAND = 'KICK %s %s %s';

    /**
     * @var string
     */
    protected $channel;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param string $server
     * @param string $channel
     * @param string $target
     * @param string $message
     */
    public function __construct($server, $channel, $target, $message = '')
    {
        $this->server = $server;
        $this->channel = $channel;
        $this->target = $target;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->channel, $this->target, $this->message);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'kick';
    }
}
