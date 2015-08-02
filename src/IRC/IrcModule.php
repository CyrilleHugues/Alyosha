<?php

namespace Alyosha\IRC;

use Alyosha\Core\Event\EventInterface;
use Alyosha\Core\Module\AbstractModule;
use Alyosha\IRC\Event\IrcCommandEvent;

class IrcModule extends AbstractModule
{
    const NAME = 'irc_module';

    /**
     * @var Server[]
     */
    protected $servers = [];

    /**
     * Start the module by creating sockets and such actions with the given config
     */
    public function start(array $config)
    {
        foreach ($config['irc']['servers'] as $name => $serverConfig) {
            $this->servers[$name] = new Server($name, $serverConfig);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Return events created during the notification phase
     *
     * @return EventInterface[]
     */
    public function getEvents()
    {
        $events = [];
        foreach ($this->servers as $server) {
            $serverEvents = $server->getEvents();
            if (empty($serverEvents))
                continue;
            $events = array_merge($events, $serverEvents);
        }

        return $events;
    }

    /**
     * Return an array of the name, the module is subscribed to.
     *
     * @return array
     */
    public function getSubscriptions()
    {
        return [
            self::NAME.'.command.message',
            self::NAME.'.command.part',
            self::NAME.'.command.join',
            self::NAME.'.command.quit',
            self::NAME.'.command.kick',
            self::NAME.'.command.invite',
        ];
    }

    /**
     * Notify the module of an event
     *
     * @param EventInterface $event
     */
    public function notify(EventInterface $event)
    {
        if ($event instanceof IrcCommandEvent) {
            $server = $event->getServer();
            if (array_key_exists($server, $this->servers))
                $this->servers[$server]->execute($event->getCommand());
        }
    }
}
