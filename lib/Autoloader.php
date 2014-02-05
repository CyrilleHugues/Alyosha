<?php

define('BASE_PATH', realpath(dirname(__FILE__)));
spl_autoload_register(
    function ($class)
    {
        $filename = BASE_PATH.'/'.str_replace('\\', '/', $class).'.php';
        include $filename;
    }
);
