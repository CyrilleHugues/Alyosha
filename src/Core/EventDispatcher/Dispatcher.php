<?php

namespace Alyosha\Core\EventDispatcher;

use Alyosha\Core\Event\EventInterface;
use Alyosha\Core\KernelEvents;
use Alyosha\Core\Module\ModuleInterface;

class Dispatcher
{
    /**
     * @var ModuleInterface[]
     */
    protected $modules = [];

    /**
     * @var ModuleInterface[][]
     */
    protected $subscribers = [];

    /**
     * @param bool
     */
    public $shouldHalt = false;

    /**
     * @param ModuleInterface[] $modules
     */
    public function setListeners(array $modules)
    {
        foreach ($modules as $module) {
            $moduleName = $module->getName();
            $this->modules[$moduleName] = $module;

            foreach ($module->getSubscriptions() as $subscription) {
                if (!array_key_exists($subscription, $this->subscribers)) {
                    $this->subscribers[$subscription] = [];
                }
                $this->subscribers[$subscription][] = $moduleName;
            }
        }
    }

    public function beat()
    {
        $events = $this->retrieveEvents();
        $this->consumeEvents($events);
    }

    /**
     * @return EventInterface[]
     */
    protected function retrieveEvents()
    {
        $events = [];
        foreach ($this->modules as $module){
            $moduleEvents = $module->getEvents();

            if (empty($moduleEvents)) continue;
            $events = array_merge($events, $moduleEvents);
        }

        return $events;
    }

    /**
     * @param EventInterface[] $events
     */
    protected function consumeEvents(array $events)
    {
        foreach ($events as $event) {
            $this->handleKernelEvents($event);
            $this->notifySubscribers($event);
        }
    }

    /**
     * @param EventInterface $event
     */
    protected function handleKernelEvents(EventInterface $event)
    {
        if (KernelEvents::HALT === $event->getName())
            $this->shouldHalt = true;
    }

    /**
     * @param EventInterface $event
     */
    protected function notifySubscribers(EventInterface $event)
    {
        $eventName = $event->getName();
        if (array_key_exists($eventName, $this->subscribers) && !empty($this->subscribers[$eventName])){
            foreach ($this->subscribers[$eventName] as $moduleName) {
                $this->modules[$moduleName]->notify($event);
            }
        }
    }
}
