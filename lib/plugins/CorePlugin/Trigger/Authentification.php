<?php

namespace plugins\CorePlugin\Trigger;

use Classes\Trigger;
use plugins\CorePlugin\Config;

class Authentification implements Trigger 
{
    var $plugin;
    public function __construct($pluginName) {
        $this->plugin = $pluginName;
    }

    public function getEvents() {
        return array("AUTH");
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
