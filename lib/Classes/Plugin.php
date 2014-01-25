<?php

namespace Classes;

class Plugin
{
    public static function init($pluginName){
        echo "Loading plugin ".$pluginName."\n";
        // Ajout des events
        $eventFolder = dir("lib/plugins/".$pluginName."/Event");
        echo "\tChemin: ".$eventFolder->path."\n";
        $event = array();
        echo "\tEvents\n";
        while (($file = $eventFolder->read()) !== false)
        {
            if (is_file($eventFolder->path.'/'.$file))
            {
                echo "\t\tLoading $file\n";
                $file = str_replace(".php", "", $file);
                $class = 'plugins\\'.$pluginName.'\\Event\\'.$file;
                array_push($event, new $class($pluginName));
            }
        }
        $eventFolder->close();
        
        // Ajout des trigggers
        $triggerFolder = dir("lib/plugins/".$pluginName."/Trigger");
        $trigger = array();
        echo "\tTrigger\n";
        while (($file = $triggerFolder->read()) !== false)
        {
            if (is_file($triggerFolder->path.'/'.$file))
            {
                echo "\t\tLoading $file\n";
                $file = str_replace(".php", "", $file);
                $class = "plugins\\".$pluginName."\\Trigger\\$file";
                array_push($trigger, new $class($pluginName));
            }
        }
        $triggerFolder->close();
        
        return array($event,$trigger);
    }
}