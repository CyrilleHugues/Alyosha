<?php

namespace App\Plugin;

class Plugin 
{
    public $pluginName = "default";
    protected $triggers = array();


    public function __construct() 
    {
    }
    
    /**
     * Retourne les events detectés
     * @param array $input
     * @return array
     */
    public function getEvents(array $input)
    {
    }

    /**
     * Fait les actions correspondantes aux events détectés
     * @param array $events
     */
    public function processEvents(array $events, array $input)
    {
        $messages = array();
        
        foreach ($events as $ev) {
            $msgs = array();
            if (array_key_exists($ev, $this->triggers)) {
                $callback = $this->triggers[$ev];
                $msgs = $this->$callback($input);
            }
            if ($msgs == null)
                continue;
            
            foreach ($msgs as $msg) {
                $messages[] = $msg;
            }
        }
        
        return $messages;
    }
}
