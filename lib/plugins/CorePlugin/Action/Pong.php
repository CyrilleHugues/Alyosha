<?php

namespace plugins\CorePlugin\Trigger;

use Classes\Trigger;

class Pong extends Trigger
{
    public function getEvents() 
    {
        return array("CorePluginPING");
    }
    
    public function process($event, array $input) 
    {
        return array(array('PONG',$input[1]));
    }
}
