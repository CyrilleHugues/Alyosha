<?php

namespace Alyosha\Core;

use Alyosha\Modules\IRC\IrcModule;
use Alyosha\Modules\Chatbot\ChatModule;

/**
 * Container is a singleton class referencing all the modules of the bot.
 * Its the heart of the app, beating and irrigating modules with events
 */
class Container
{
    private $willHalt = false;
    private $modules = [];
    private $subscribers = [];

    public function __construct()
    {
        $this->modules = [
            new IrcModule(),
            new ChatModule(),
        ];

        $this->checkIfModulesAreCompatible($this->modules);

        foreach ($this->modules as $key => $module) {
            foreach ($module->getTriggers() as $eventName) {
                if (!array_key_exists($eventName, $this->subscribers)) {
                    $this->subscribers[$eventName] = [];
                }
                $this->subscribers[$eventName][] = $key;
            }
        }

        $this->instance = $this;
    }

    public function checkIfModulesAreCompatible(array $modules) {
        foreach ($modules as $module) {
            if ( ! $module instanceof ModuleInterface){
                print $module->getName() . "does not implement ModuleInterface.\n";
                exit();
            }
        }
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
            $this->notify($event);
            if ($event->isHaltSignal()) {
                $this->willHalt = true;
            }
        }
    }

    private function notify(Event $event)
    {
        if (!array_key_exists($event->getName(), $this->subscribers)) return;
        foreach ($this->subscribers[$event->getName()] as $key) {
            $this->modules[$key]->notify($event);
        }
    }

    public function fire()
    {
        array_map(
            function($o){$o->fire();},
            $this->modules
        );
    }

    public function willHalt()
    {
        return $this->willHalt;
    }
}

