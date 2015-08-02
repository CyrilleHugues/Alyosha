<?php

namespace Alyosha\Core\Module;

use Alyosha\Core\Event\EventInterface;

abstract class AbstractModule implements ModuleInterface
{
    /**
     * Start the module by creating sockets and such actions with the given config
     */
    public function start(array $config)
    {
    }

    /**
     * Return events created during the notification phase
     *
     * @return EventInterface[]
     */
    public function getEvents()
    {
        return [];
    }

    /**
     * Return an array of the name, the module is subscribed to.
     *
     * @return array
     */
    public function getSubscriptions()
    {
        return [];
    }

    /**
     * Notify the module of an event
     *
     * @param EventInterface $event
     */
    public function notify(EventInterface $event)
    {
    }
}
