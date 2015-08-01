<?php

namespace Alyosha\Modules\IrcSecurity;

use Alyosha\Modules\IRC\IrcEvent;

class AdminEvent extends IrcEvent
{
    public function __construct(IrcEvent $event){
        $this->name = "irc.admin_message";
        $this->action = $event->getAction();
        $this->hostname = $event->getHostname();
        $this->channel = $event->getAction();
        $this->target = $event->getTarget();
        $this->message = $event->getMessage();
        $this->username = $event->getUsername();
    }
} 