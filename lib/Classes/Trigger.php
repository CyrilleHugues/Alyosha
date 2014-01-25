<?php

namespace Classes;

abstract class Trigger
{
    var $plugin;
    public function __construct($pluginName)
    {
        $this->plugin = $pluginName;
    }
    public abstract function getEvents();
    public abstract function process($event, array $input);
}