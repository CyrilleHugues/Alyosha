<?php

namespace App\Core;

use Exception;

class Alyosha
{
    private $connexion;
    private $plugins = array();
    
    public function __construct() 
    {
        // Juste pour que le container soit initialisé
        $c = Container::getInstance();
    }
    
    // A Faire: unload les plugins qui failent avec un try catch
    public function run()
    {
        $this->connexion = new Connexion(\Config::$cfg['server'], \Config::$cfg['port']);
        while (true)
        {
            $input = $this->connexion->receive();
            if (count($input) == 0)
                continue;
            
            $events = array();
            foreach (Container::getInstance()->plugins as $plugin) 
            {
                try {
                    $evs = $plugin->getEvents($input);
                    if ($evs != null){
                        $events = array_merge($events,$evs);
                    }
                }
                catch (Exception $e)
                {
                    $key = array_search($plugin, Container::getInstance()->plugins);
                    if ($key != FALSE)
                    {
                        unset(Container::getInstance()->plugins[$key]);
                    }
                }
            }
            $events = array_unique($events);
            //echo implode(" ", $input);
            if (count($events)==0)
            {
                continue;
            }
            
            $messages = array();
            foreach (Container::getInstance()->plugins as $plugin)
            {
                try {
                    $msgs = $plugin->processEvents($events,$input);
                    if ($msgs != null){
                        $messages = array_merge($messages, $msgs);
                    }
                }
                catch (Exception $e){
                    $key = array_search($plugin, Container::getInstance()->plugins);
                    if ($key != FALSE)
                    {
                        unset(Container::getInstance()->plugins[$key]);
                    }
                }
            }
            
            $this->connexion->send($messages);
            
            // detection d'un message quit
            foreach ($messages as $message)
            {
                if ($message[0] == "QUIT"){
                    exit();
                }
            }
        }
    }
}