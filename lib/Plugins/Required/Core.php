<?php

namespace Plugins\Required;

use App\Plugin\Plugin;
use App\Core\Container;

class Core extends Plugin
{
    public $pluginName = "Core";
    protected $triggers = array(
        "Core/PING" => "callBackPong",
        "Core/IDENT" => "callBackIdentification",
    );


    public function getEvents(array $input) 
    {
        $events = array();
        // PING
        if ($input[0] == "PING")
        {
            $events[] = $this->pluginName."/PING";
        }
        
        // Demande d'identification
        if (count($input)>=5 && $input[4]=="Found")
        {
            $events[] = $this->pluginName."/IDENT";
        }
        return $events;
    }

    public static function callBackIdentification($input)
    {
        $messages = array();
        $nickname = \Config::$cfg['nickname'];
        $messages[] = array("USER","$nickname agrume.pl $nickname :$nickname");
        $messages[] = array("NICK","$nickname");
        return $messages;
    }

    public static function callBackPong($input)
    {
        return array(array('PONG',$input[1]));
    }
}
