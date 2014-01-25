<?php

namespace Classes;

interface Trigger
{
    public function __construct($pluginName);
    public function getEvents();
    public function process($event, array $input);
}