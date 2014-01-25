<?php

namespace plugins\CorePlugin\Trigger;

use Classes\Trigger;

class Pong implements Trigger
{
    var $plugin;
    public function __construct($pluginName) {
        $this->plugin = $pluginName;
    }

    public function process($event, array $input) {
        return array(array('PONG',$input[1]));
    }

    public function getEvents() 
    {
        return array("PING");
    }

}
