<?php

namespace plugins\CorePlugin\Trigger;

use Classes\Trigger;

class Identification extends Trigger 
{
    public function getEvents() 
    {
        return array("CorePluginIDENT");
    }

    public function process($event, array $input) {
        $config = Config::$config;
        $nickname = $config['nickname'];
        // sale
        $auth=array("USER","$nickname agrume.pl $nickname :$nickname");
        $nick=array("NICK","$nickname");
        return array($auth,$nick);
    }
}
