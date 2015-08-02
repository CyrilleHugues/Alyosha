<?php

namespace Alyosha\IRC\Event;

use Alyosha\Core\Event\AbstractEvent;
use Alyosha\IRC\IrcModule;
use Alyosha\IRC\Message\IrcMessage;

class IrcEvent extends AbstractEvent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var IrcMessage
     */
    protected $ircMessage;

    /**
     * @param $name
     * @param IrcMessage $ircMessage
     */
    public function __construct($name, IrcMessage $ircMessage)
    {
        $this->name = $name;
        $this->ircMessage = $ircMessage;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return IrcModule::NAME.'.event.'.$this->name;
    }

    /**
     * @return IrcMessage
     */
    public function getIrcMessage()
    {
        return $this->ircMessage;
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->ircMessage->getServer();
    }
}
