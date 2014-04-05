<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 05/04/14
 * Time: 20:19
 */

namespace App\Events;

use App\Core\Container;

class PluginWorkerEvent
{
    public function getEvents(array $input)
    {
        $events = array();

        if (count($input)>=2 && $input[1] == "PRIVMSG")
        {
            $infos = Container::extractMsgInfo($input);
            $message = explode(' ', $infos['message']);
            // si c'est une query
            if ($infos['where'] == \Config::$cfg['nickname'] && count($message)>=2)
            {
                if ('!load' == trim($message[0]))
                {
                    $events[] = "PluginWorker/LOAD";
                }

                if ('!unload' == trim($message[0]))
                {
                    $events[] = "PluginWorker/UNLOAD";
                }
            }
        }
        return $events;
    }
} 