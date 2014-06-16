<?php

namespace Plugins\Required;

use App\Core\Plugin;
use App\Core\Container;

class Security extends Plugin
{
    public $pluginName = "Security";
    protected $triggers = array(
        'Security/LOGIN' => 'callBackLogin',
        'Security/LOGOUT' => 'callBackLogout',
        'Security/EXIT' => 'callBackExit'
    );
    
    public function getEvents(array $input) {
        $events = array();
        
        if (count($input)>=2 && $input[1] == "PRIVMSG")
        {
            $infos = Container::extractMsgInfo($input);
            // si c'est une query
            if ($infos['where'] == \Config::$cfg['nickname'])
            {
                if (preg_match('/^!login/', $infos['message'])==1)
                {
                    $events[] = $this->pluginName."/LOGIN";
                }
                
                if (preg_match('/^!logout$/', $infos['message'])==1)
                {
                    $events[] = $this->pluginName."/LOGOUT";
                }
            }
            if (preg_match('/^!exit/', $infos['message'])==1)
            {
                $events[] = $this->pluginName."/EXIT";
            }
        }
        return $events;
    }

    public function callBackLogin($input)
    {
        $infos = Container::extractMsgInfo($input);
        $message = explode(' ', $infos['message']);
        
        $reponse = array();
        if (count($message) >= 2 && trim($message[1]) == \Config::$cfg['adminPassword'])
        {
            Container::getInstance()->set('admin',$infos['hostname']);
            $reponse[] = array('PRIVMSG',$infos['who']." :Tu es authentifié.");
        }
        return $reponse;
    }
    
    public function callBackLogout($input)
    {
        $infos = Container::extractMsgInfo($input);
        
        if ($infos['hostname'] == Container::getInstance()->get('admin'))
        {
            Container::getInstance()->set('admin','');
        }
        return array(array('PRIVMSG',$infos['who']." :Bye."));
    }
    
    public function callBackExit($input)
    {
        $infos = Container::extractMsgInfo($input);
        $messages = array();
        $quote = preg_replace('/!exit/', '', $infos['message']);
        if ($infos['hostname'] == Container::getInstance()->get('admin'))
        {
            $messages[] = array('PRIVMSG', $infos['who']." :Bye.");
            if ($quote == ""){
                $messages[] = array('QUIT', "q+");
            }
            else {
                $messages[] = array('QUIT', ':'.trim($quote));
            }
        }
        return $messages;
    }
}
