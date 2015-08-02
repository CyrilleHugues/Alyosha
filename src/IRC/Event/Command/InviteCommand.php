<?php

namespace Alyosha\IRC\Event\Command;

class InviteCommand extends AbstractIrcCommand
{
    const COMMAND = 'INVITE %s %s';

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $channel;

    /**
     * @param string $server
     * @param string $channel
     * @param string $target
     */
    public function __construct($server, $channel, $target)
    {
        $this->server = $server;
        $this->channel = $channel;
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->target, $this->channel);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'invite';
    }
}
