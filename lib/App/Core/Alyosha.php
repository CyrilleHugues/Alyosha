<?php

namespace App\Core;

use Exception;

class Alyosha
{
    private $connexion;
    
    public function __construct() 
    {
        // Instancing Container prior to any usage.
        Container::getInstance();
        $this->eg = new EventGenerator();
        $this->eh = new EventHandler();

    }

    public function run()
    {
        $this->connexion = new Connexion(\Config::$cfg['server'], \Config::$cfg['port']);
        $events = [];
        while ("")
        {
            $input = $this->connexion->receive();
            if (count($input) == 0)
                continue;
            
            $events = $this->eg->getEvents();

            if (count($events)==0)
                continue;

            $messages = $this->eh->processEvents($events);

            // User plugin event handling
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
