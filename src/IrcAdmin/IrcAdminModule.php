<?php

namespace Alyosha\IrcAdmin;

use Alyosha\Core\Event\EventInterface;
use Alyosha\Core\Module\AbstractModule;
use Alyosha\IRC\Event\Command\InviteCommand;
use Alyosha\IRC\Event\Command\IrcCommandInterface;
use Alyosha\IRC\Event\Command\JoinCommand;
use Alyosha\IRC\Event\Command\KickCommand;
use Alyosha\IRC\Event\Command\MessageCommand;
use Alyosha\IRC\Event\Command\PartCommand;
use Alyosha\IRC\Event\Command\QuitCommand;
use Alyosha\IRC\Event\IrcCommandEvent;
use Alyosha\IRC\Event\IrcEvent;
use Alyosha\IrcAdmin\Authentication\Authenticator;
use Alyosha\IrcAdmin\Event\AdminEvent;

class IrcAdminModule extends AbstractModule
{
    const NAME = "irc_admin_module";
    const SYMBOL = ';';
    const AUTH_SUCCESS = 'You have been authenticated successfully.';

    /**
     * @var Authenticator
     */
    protected $authenticator;

    /**
     * @var EventInterface[]
     */
    protected $events;

    /**
     * @param array $config
     */
    public function start(array $config)
    {
        $this->authenticator = new Authenticator($config['security']['password']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return array
     */
    public function getSubscriptions()
    {
        return [
            'irc_module.event.private_message',
            'irc_module.event.public_message',
            'irc_module.event.hilight_message'
        ];
    }

    /**
     * @param EventInterface $event
     */
    public function notify(EventInterface $event)
    {
        if ($event instanceof IrcEvent) {
            if ($this->authenticator->isAdmin($event)){
                $this->createAuthEvent($event);
                $this->handleEvent($event);
            } elseif (
                $event->getName() == 'irc_module.event.private_message'
                && $this->authenticator->auth($event)
            ){
                $this->isNewAuthEvent($event);
            }
        }
    }

    /**
     * @param IrcEvent $event
     */
    public function handleEvent(IrcEvent $event)
    {
        $message = $event->getIrcMessage()->getMessage();
        if (substr($message, 0, 1) !== self::SYMBOL)
            return;

        $matches = [];
        $ok = preg_match('/;([^ ]+) ?(.*)?/', $message, $matches);
        if ($ok == 1){
            $this->handleCommand($event, $matches[1], explode(' ', $matches[2]));
        }
    }

    /**
     * @param IrcEvent $event
     * @param string $commandName
     * @param array $args
     */
    public function handleCommand(IrcEvent $event, $commandName, $args)
    {
        $command = null;
        $server = $event->getServer();
        switch($commandName){
            case 'join':
                $command = new JoinCommand($server, array_shift($args));
                break;
            case 'part':
                $command = new PartCommand($server, array_shift($args), join(' ', $args));
                break;
            case 'quit':
                $command = new QuitCommand($server, join(' ', $args));
                break;
            case 'invite':
                $command = new InviteCommand($server, array_shift($args), array_shift($args));
                break;
            case 'kick':
                $command = new KickCommand($server, array_shift($args), array_shift($args), join(' ', $args));
        }

        if ($command !== null)
            $this->createCommandEvent($command);
    }

    /**
     * @param IrcCommandInterface $command
     */
    public function createCommandEvent(IrcCommandInterface $command)
    {
        $ircCommandEvent = new IrcCommandEvent($command);
        $this->events[] = $ircCommandEvent;
    }

    /**
     * @return EventInterface[]
     */
    public function getEvents()
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    /**
     * @param IrcEvent $event
     */
    public function createAuthEvent(IrcEvent $event)
    {
        $this->events[] = new AdminEvent('message', $event->getIrcMessage());
    }

    /**
     * @param IrcEvent $event
     */
    public function isNewAuthEvent(IrcEvent $event)
    {
        $welcomeCommand = new MessageCommand(
            $event->getServer(),
            $event->getIrcMessage()->getUsername(),
            self::AUTH_SUCCESS
        );
        $this->events[] = new IrcCommandEvent($welcomeCommand);
        $this->createAuthEvent($event);
    }
}
