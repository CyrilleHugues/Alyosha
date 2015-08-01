<?php

namespace Alyosha\Modules\IRC;

use Alyosha\Core\Event;

class IrcCommand extends Event
{
    protected $name = "irc.command";

    protected $command;

    public function __construct($command)
    {
        return $this->command = $command;
    }

    public function getCommand()
    {
        return $this->command;
    }
}
