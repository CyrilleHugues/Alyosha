<?php

require 'lib/Autoloader.php';

$config = include 'lib/Config.php';;

$bot = new Core($config);
$bot->run();