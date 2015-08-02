<?php

namespace Alyosha\IRC\Event;

use Alyosha\IRC\Event\Command\IrcCommandInterface;
use Alyosha\Core\Event\AbstractEvent;
use Alyosha\IRC\IrcModule;

class IrcCommandEvent extends AbstractEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var IrcCommandInterface
     */
    protected $ircCommand;

    /**
     * @param IrcCommandInterface $ircCommand
     */
    public function __construct(IrcCommandInterface $ircCommand)
    {
        $this->name = $ircCommand->getCommandName();
        $this->ircCommand = $ircCommand;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return IrcModule::NAME.'.command.'.$this->name;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->ircCommand->getCommand();
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->ircCommand->getServer();
    }
}
