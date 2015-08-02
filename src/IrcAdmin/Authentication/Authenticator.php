<?php

namespace Alyosha\IrcAdmin\Authentication;

use Alyosha\IRC\Event\IrcEvent;

class Authenticator
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $authorizedHostnames = [];

    /**
     * @param $password
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * @param IrcEvent $event
     *
     * @return bool
     */
    public function isAdmin(IrcEvent $event)
    {
        $hostname = $event->getIrcMessage()->getHostname();
        return in_array($hostname, $this->authorizedHostnames);
    }

    /**
     * @param IrcEvent $event
     *
     * @return bool
     */
    public function auth(IrcEvent $event)
    {
        $matches = [];
        $ok = preg_match('/^;auth (.+)/', $event->getIrcMessage()->getMessage(), $matches);
        if ($ok == 1 && $matches[1] == $this->password){
            $this->authorizedHostnames[] = $event->getIrcMessage()->getHostname();
            return true;
        }

        return false;
    }
}
