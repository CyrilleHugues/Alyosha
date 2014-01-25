<?php

namespace plugins\CorePlugin\Event;

use Classes\Event;

class NeedAuth implements Event
{
    var $name = "AUTH";
    var $plugin;
    public function __construct($pluginName) {
        $this->plugin = $pluginName;
    }

    public function getName() {
        return $this->name;
    }

    public function isHappening(array $input) {
        $resp = false;
        if (count($input)==8 && $input[4]=="Found")
        {
            $resp = true;
        }
        return $resp;
    }

}
