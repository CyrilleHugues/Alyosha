<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 05/04/14
 * Time: 20:23
 */

namespace App\Core;

use App\Events\ChannelEvent;
use App\Events\CoreEvent;
use App\Events\PluginWorkerEvent;
use App\Events\SecurityEvent;

class EventGenerator {
    public function __construct()
    {
        $this->eventCreators = [];
        $this->eventCreators[] = new ChannelEvent();
        $this->eventCreators[] = new CoreEvent();
        $this->eventCreators[] = new PluginWorkerEvent();
        $this->eventCreators[] = new SecurityEvent();
    }

    public function getEvents()
    {
        $events= [];
        foreach ($this->eventCreators as $ec)
        {
            $events = array_merge($events, $ec->getEvents());
        }
        return array_unique($events);
    }
} 