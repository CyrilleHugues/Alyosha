<?php

namespace Alyosha\IRC\Event;

use Alyosha\IRC\Event\Command\IrcCommandInterface;
use Alyosha\Core\Event\AbstractEvent;
use Alyosha\IRC\IrcModule;

class IrcCommandEvent extends AbstractEvent
{
    protected $name;
    protected $ircCommand;

    public function __construct(IrcCommandInterface $ircCommand)
    {
        $this->name = $ircCommand->getCommandName();
        $this->ircCommand = $ircCommand;
    }

    public function getName()
    {
        return IrcModule::NAME.'.command.'.$this->name;
    }

    public function getCommand()
    {
        return $this->ircCommand->getCommand();
    }

    public function getServer()
    {
        return $this->ircCommand->getServer();
    }
}
