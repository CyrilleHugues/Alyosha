<?php

namespace plugins\CorePlugin\Event;

use Classes\Event;

class Ping extends Event
{
    var $name = "PING";

    public function isHappening(array $input) {
        $resp = false;
        if ($input[0] == "PING")
        {
            $resp = true;
        }
        return $resp;
    }
}
