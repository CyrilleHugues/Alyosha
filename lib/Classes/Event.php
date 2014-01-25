<?php

namespace Classes;

interface Event
{
    public function __construct($pluginName);
    public function getName();
    public function isHappening(array $input);
}