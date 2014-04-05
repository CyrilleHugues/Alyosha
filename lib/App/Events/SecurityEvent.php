<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 05/04/14
 * Time: 20:16
 */

namespace App\Events;

use App\Core\Container;

class SecurityEvent
{
    public function getEvents(array $input)
    {
        $events = array();

        if (count($input)>=2 && $input[1] == "PRIVMSG")
        {
            $infos = Container::extractMsgInfo($input);
            // si c'est une query
            if ($infos['where'] == \Config::$cfg['nickname'])
            {
                if (preg_match('/^!login/', $infos['message'])==1)
                {
                    $events[] = "Security/LOGIN";
                }

                if (preg_match('/^!logout$/', $infos['message'])==1)
                {
                    $events[] = "Security/LOGOUT";
                }
            }
            if (preg_match('/^!exit/', $infos['message'])==1)
            {
                $events[] = "Security/EXIT";
            }
        }
        return $events;
    }
} 