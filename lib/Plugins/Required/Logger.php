<?php

namespace Plugins\Required;

use App\Plugin\Plugin;

class Logger extends Plugin
{
    public $pluginName = "Logger";
    
    public function processEvents(array $events, array $input) {
        echo implode(" ", $events)."\n";
    }

}
