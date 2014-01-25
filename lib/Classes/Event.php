<?php

namespace Classes;

abstract class Event
{
    public $plugin;
    public $name;
    public function __construct($pluginName)
    {
        $this->plugin = $pluginName;
    }
    public function getName()
    {
        return $this->plugin.$this->name;
    }
    public abstract function isHappening(array $input);
}