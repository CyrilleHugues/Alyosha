<?php

namespace Alyosha\IRC\Event\Command;

class PartCommand extends AbstractIrcCommand
{
    const COMMAND = 'PART %s %s';

    /**
     * @var string
     */
    protected $channel;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param string $server
     * @param string $channel
     * @param string $message
     */
    public function __construct($server, $channel, $message = '')
    {
        $this->server = $server;
        $this->channel = $channel;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->channel, $this->message);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'part';
    }
}