<?php

namespace plugins\CorePlugin\Event;

use Classes\Event;

class NeedIdent extends Event
{
    public $name = "IDENT";

    public function isHappening(array $input) {
        $resp = false;
        if (count($input)==8 && $input[4]=="Found")
        {
            $resp = true;
        }
        return $resp;
    }

}
