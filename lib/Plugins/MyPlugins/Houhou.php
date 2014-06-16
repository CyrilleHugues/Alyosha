<?php

namespace Plugins\MyPlugins;

use App\Core\Plugin;
use App\Core\Container;

class Houhou extends Plugin
{
    public $pluginName = "Houhou";
    public $triggers = array(
        'Channel/QUERY' => 'repeat',
        'Channel/JOIN' => 'greetings',
        'Channel/HILIGHT' => 'oh_hi'
    );
    
    
    public function repeat($input){
        $infos = Container::extractMsgInfo($input);
        if ($infos['hostname'] != Container::getInstance()->get('admin')){
            return array(array('PRIVMSG',$infos['who']." :".$infos['message']));
        }
    }

    public function greetings($input)
    {
        $chan = trim(preg_replace('/:/',"",$input[2]));
        $nick = trim(preg_replace("/(:|!.*)/", "", $input[0]));
        if ($nick != \Config::$cfg['nickname'])
        {
            return array(array('PRIVMSG',"$chan :Bienvenue $nick o/"));
        }
    }
    
    public function oh_hi($input)
    {
        $infos = Container::extractMsgInfo($input);
        
        $heys = array(
            "Hey!",
            "Wesh",
            "C'est fini oui ?",
        );
        
        $reponse = $heys[rand(0, count($heys)-1)];
        
        $messages = array();
        $messages[] = array('PRIVMSG',$infos['where']." :".$reponse);
        return $messages;
    }
}
