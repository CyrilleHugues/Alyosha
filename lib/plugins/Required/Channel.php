<?php

namespace Plugins\Required;

use App\Plugin\Plugin;
use App\Core\Container;

class Channel extends Plugin
{
    public $pluginName = "Channel";
    protected $triggers=array(
        "Channel/MOTD_END" => "callBackJoins",
        "Channel/DISPLAY_TOPIC" => "callBackClearUsersOnChan",
        "Channel/NAMES" => "callBackAddUsersOnChan",
        "Channel/JOIN" => "callBackAddUserOnChan",
        "Channel/PART" => "callBackDeleteUserOnChan",
        "Channel/KICK" => "callBackDepopUser",
        "Channel/WAS_INVITED" => "callBackInvitation"
    );

    public function getEvents(array $input) {
        $events = array();
        if (count($input) >= 2)
        {
            switch ($input[1]) {
                // Fin du MOTD
                case '376':
                    $events[] = $this->pluginName."/MOTD_END";
                    break;
                
                // Affichage du topic
                case '332':
                    $events[] = $this->pluginName."/DISPLAY_TOPIC";
                    break;
                
                // Affichage de la date et de l'auteur de la derniere modification du topic
                case '333':
                    $events[] = $this->pluginName."/DISPLAY_TOPIC_INFO";
                    break;
                
                // Affichage des users sur le chan
                case '353':
                    $events[] = $this->pluginName."/NAMES";
                    break;
                
                // Fin d'affichage des users sur le chan
                case '366':
                    $events[] = $this->pluginName."/NAMES_END";
                    break;
                
                // Join d'un user sur le chan
                case 'JOIN':
                    $events[] = $this->pluginName."/JOIN";
                    break;
                
                // Depart d'un user du chan
                case 'PART':
                    $events[] = $this->pluginName."/PART";
                    break;
                
                // Kick d'un user d'un chan
                case 'KICK':
                    $events[] = $this->pluginName."/KICK";
                    break;
                    
                // message
                case 'PRIVMSG':
                    $events[] = $this->pluginName."/PRIVMSG";
                    $infos = Container::extractMsgInfo($input);
                    if ($infos['where'] == \Config::$cfg['nickname'])
                    {
                        $events[] = $this->pluginName."/QUERY";
                        if ($infos['hostname'] == Container::getInstance()->get('admin'))
                        {
                            $events[] = $this->pluginName."/SECURED_QUERY";
                        }
                    }
                    else
                    {
                        $events[] = $this->pluginName."/PUBLIC";
                        if (preg_match('/'.\Config::$cfg['nickname'].'/', $infos['message']) == 1)
                        {
                            $events[] = $this->pluginName."/HILIGHT";
                        }
                    }
                    break;
                    
                case 'KICK':
                    $events[] = $this->pluginName."/KICK";
                    $infos = Container::extractMsgInfo($input);
                    if ($infos['cible'] == \Config::$cfg['nickname']){
                        $events[] = $this->pluginName."/WAS_KICKED";
                    }
                    break;
                case 'INVITE':
                    $events[] = $this->pluginName."/INVITE";
                    $infos = Container::extractMsgInfo($input);
                    if ($infos['where'] == \Config::$cfg['nickname']){
                        $events[] = $this->pluginName."/WAS_INVITED";
                    }
                    break;
            }
        }
        return $events;
    }
    
    public function callBackJoins($input)
    {
        $messages = array();
        foreach (\Config::$cfg['chans'] as $chan)
        {
            $messages[] = array('JOIN',$chan);
        }
        return $messages;
    }
    
    public function callBackClearUsersOnChan($input)
    {
        Container::getInstance()->clearUsersOnChan($input[3]);
    }
    
    public function callBackAddUsersOnChan($input)
    {
        $dbldot = explode(':',  implode(" ", $input));
        $infos = explode(" ", $dbldot[1]);
        $chan = $infos[count($infos)-2];
        foreach (explode(" ", $dbldot[2]) as $name)
        {
            $user = trim(preg_replace("[@+]","", $name));
            if ($user == "")
            {
                continue;
            }
            Container::getInstance()->addUserOnChan($user,$chan);
        }
    }
    
    public function callBackDeleteUserOnChan($input)
    {
        $chan = $input[2];
        $nick = trim(preg_replace("/(:|!.*)/", "", $input[0]));
        Container::getInstance()->deleteUserOnChan($nick, $chan);
    }
    
    public function callBackAddUserOnChan($input)
    {
        $chan = trim(preg_replace('/:/',"",$input[2]));
        $nick = trim(preg_replace("/(:|!.*)/", "", $input[0]));
        Container::getInstance()->addUserOnChan($nick, $chan);
    }
    
    public function callBackDepopUser($input)
    {
        $infos = Container::extractMsgInfo($input);
        Container::getInstance()->deleteUserOnChan($infos['cible'],$infos['where']);
    }
    
    public function callBackInvitation($input)
    {
        $infos = Container::extractMsgInfo($input);
        $messages = array();
        $messages[] = array('JOIN',$infos['message']);
        return $messages;
    }
}
