<?php

namespace Alyosha\Core;

/**
 * EventDispatcher is a class referencing all the modules of the bot.
 * Its the heart of the app, beating and irrigating modules with events
 */
class EventDispatcher implements ModuleInterface
{
    private $willHalt = false;
    private $modules = [];
    private $subscribers = [];
    private $queue = [];

    public function __construct(array $modules)
    {
        $this->modules = $modules;
        $this->modules[] = $this;

        if ( ! $this->checkIfModulesAreCompatible($this->modules)){
            print "One module does not implement ModuleInterface.\n";
            $this->willHalt = true;
            $this->modules = [];
            return;
        }

        foreach ($this->modules as $key => $module) {
            foreach ($module->getTriggers() as $eventName) {
                if (!array_key_exists($eventName, $this->subscribers)) {
                    $this->subscribers[$eventName] = [];
                }
                $this->subscribers[$eventName][] = $key;
            }
        }
        print "Finished initialisation.\n";
    }

    public function checkIfModulesAreCompatible(array $modules) {
        foreach ($modules as $module) {
            if ( ! $module instanceof ModuleInterface){
                return false;
            }
        }
        return true;
    }

    public function beat()
    {
        /** @var Event[] $events */
        $events = [];
        foreach ($this->modules as $module) {
            $moduleEvents = $module->getEvents();
            if (empty($moduleEvents)) continue;
            $events = array_merge($events, $moduleEvents);
        }

        foreach ($events as $event) {
            print $event->getName()."\n";
            $this->notifySubscribers($event);
            if ($event->isHaltSignal()) {
                $this->willHalt = true;
            }
        }
    }

    private function notifySubscribers(Event $event)
    {
        if (!array_key_exists($event->getName(), $this->subscribers)) return;
        foreach ($this->subscribers[$event->getName()] as $key) {
            $this->modules[$key]->notify($event);
        }
    }

    public function fireModules()
    {
        array_map(
            function($o){$o->fire();},
            $this->modules
        );
        print "All modules have been fired.\n";
    }

    public function willHalt()
    {
        return $this->willHalt;
    }

    public function getName()
    {
        return "container";
    }

    public function getEvents()
    {
        $events = $this->queue;
        $this->queue = [];
        return $events;
    }

    public function getTriggers()
    {
        return [
            "module.unload",
            "module.load",
        ];
    }

    public function fire(){}

    /**
     * @param Event $event
     * @todo to implement
     */
    public function notify(Event $event)
    {
        switch($event->getName()){
            case "module.load":
                break;
            case "module.unload":
                break;
        }
    }
}

