<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 05/04/14
 * Time: 20:05
 */

namespace App\Events;

use App\Core\Container;

class ChannelEvent
{
    public function getEvents(array $input)
    {
        $events = [];
        if (count($input) >= 2)
        {
            switch ($input[1]) {
                // Fin du MOTD
                case '376':
                    $events[] = "Channel/MOTD_END";
                    break;

                // Affichage du topic
                case '332':
                    $events[] = "Channel/DISPLAY_TOPIC";
                    break;

                // Affichage de la date et de l'auteur de la derniere modification du topic
                case '333':
                    $events[] = "Channel/DISPLAY_TOPIC_INFO";
                    break;

                // Affichage des users sur le chan
                case '353':
                    $events[] = "Channel/NAMES";
                    break;

                // Fin d'affichage des users sur le chan
                case '366':
                    $events[] = "Channel/NAMES_END";
                    break;

                // Join d'un user sur le chan
                case 'JOIN':
                    $events[] = "Channel/JOIN";
                    break;

                // Depart d'un user du chan
                case 'PART':
                    $events[] = "Channel/PART";
                    break;

                // Kick d'un user d'un chan
                case 'KICK':
                    $events[] = "Channel/KICK";
                    break;

                // message
                case 'PRIVMSG':
                    $events[] = "Channel/PRIVMSG";
                    $infos = Container::extractMsgInfo($input);
                    if ($infos['where'] == \Config::$cfg['nickname'])
                    {
                        $events[] = "Channel/QUERY";
                        if ($infos['hostname'] == Container::getInstance()->get('admin'))
                        {
                            $events[] = "Channel/SECURED_QUERY";
                        }
                    }
                    else
                    {
                        $events[] = "Channel/PUBLIC";
                        if (preg_match('/'.\Config::$cfg['nickname'].'/', $infos['message']) == 1)
                        {
                            $events[] = "Channel/HILIGHT";
                        }
                    }
                    break;

                case 'KICK':
                    $events[] = "Channel/KICK";
                    $infos = Container::extractMsgInfo($input);
                    if ($infos['cible'] == \Config::$cfg['nickname']){
                        $events[] = "Channel/WAS_KICKED";
                    }
                    break;
                case 'INVITE':
                    $events[] = "Channel/INVITE";
                    $infos = Container::extractMsgInfo($input);
                    if ($infos['where'] == \Config::$cfg['nickname']){
                        $events[] = "Channel/WAS_INVITED";
                    }
                    break;
            }
        }
    }
} 