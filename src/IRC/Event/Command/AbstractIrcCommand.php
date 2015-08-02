<?php

namespace Alyosha\IRC\Event\Command;

abstract class AbstractIrcCommand implements IrcCommandInterface
{
    /**
     * @var string
     */
    protected $server;

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }
}
