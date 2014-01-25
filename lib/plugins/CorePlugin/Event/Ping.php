<?php

namespace plugins\CorePlugin\Event;

use Classes\Event;

class Ping implements Event
{
    var $name = "PING";
    var $plugin;
    public function __construct($pluginName) {
        $this->plugin = $pluginName;
    }

    public function getName() {
        return $this->name;
    }

    public function isHappening(array $input) {
        $resp = false;
        if ($input[0] == "PING")
        {
            $resp = true;
        }
        return $resp;
    }
}
