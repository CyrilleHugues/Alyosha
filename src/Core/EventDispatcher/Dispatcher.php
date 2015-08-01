<?php

namespace Alyosha\Core\EventDispatcher;

use Alyosha\Core\Module\ModuleInterface;

class Dispatcher
{
    /**
     * @var ModuleInterface[]
     */
    protected $modules;

    protected $subscribers;

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

    public function retrieveEvents()
    {
        $events = [];
        foreach ($this->modules as $module){
            $moduleEvents = $module->getEvents();

            if (empty($moduleEvents)) continue;
            $events->
        }

        return $events;
    }
}