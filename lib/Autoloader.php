<?php

spl_autoload_register(
    function ($class)
    {
        require 'lib/'.$class.'.class.php';
    }
);