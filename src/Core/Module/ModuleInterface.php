<?php

namespace Alyosha\Core\Module;

use Alyosha\Core\Event\EventInterface;

interface ModuleInterface
{
    /**
     * Start the module by creating sockets and such actions with the given config
     */
    public function start(array $config);

    /**
     * @return string
     */
    public function getName();

    /**
     * Return events created during the notification phase
     *
     * @return EventInterface[]
     */
    public function getEvents();

    /**
     * Return an array of the name, the module is subscribed to.
     *
     * @return array
     */
    public function getSubscriptions();

    /**
     * Notify the module of an event
     *
     * @param EventInterface $event
     */
    public function notify(EventInterface $event);
}
