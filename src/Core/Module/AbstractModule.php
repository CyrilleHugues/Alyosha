<?php

namespace Alyosha\Core\Module;


use Alyosha\Core\Event\EventInterface;

class AbstractModule implements ModuleInterface
{
    /**
     * Instantiate the module given the config
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Start the module by creating sockets and such actions
     */
    public function start()
    {
        // TODO: Implement start() method.
    }

    /**
     * Return events created during the notification phase
     *
     * @return EventInterface[]
     */
    public function getEvents()
    {
        // TODO: Implement getEvents() method.
    }

    /**
     * Notify the module of an event
     *
     * @param EventInterface $event
     */
    public function notify(EventInterface $event)
    {
        // TODO: Implement notify() method.
    }
}
