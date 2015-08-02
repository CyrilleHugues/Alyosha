<?php

namespace Alyosha\IRC\Event\Command;

class QuitCommand extends AbstractIrcCommand
{
    const COMMAND = 'QUIT :%s';

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
     * @param string $message
     */
    public function __construct($server, $message = '')
    {
        $this->server = $server;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return sprintf(self::COMMAND, $this->message);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return 'quit';
    }
}
