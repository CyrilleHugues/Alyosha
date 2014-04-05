<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 05/04/14
 * Time: 20:44
 */

namespace App\Core;


class EventHandler
{
    public function __construct()
    {
        $this->listeners = [];
    }

    public function processEvents(array $events)
    {
        $messages = [];

        // ask all listeners
        foreach ($this->listeners as $listener)
        {
            $messages = array_merge($messages, $listener->processEvents($events));
        }

        return $messages;
    }
} 