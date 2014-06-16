<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 05/04/14
 * Time: 20:12
 */

namespace App\Events;


class CoreEvent
{
    public function getEvents(array $input)
    {
        $events = [];
        // PING
        if ($input[0] == "PING")
        {
            $events[] = "Core/PING";
        }

        // Demande d'identification
        if (count($input)>=5 && $input[4]=="Found")
        {
            $events[] = "Core/IDENT";
        }
        return $events;
    }
}
