<?php

namespace Alyosha\IRC\Event\Command;

abstract class AbstractIrcCommand implements IrcCommandInterface
{
    protected $server;

    public function getServer()
    {
        return $this->server;
    }
}
