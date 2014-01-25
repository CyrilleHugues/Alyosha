<?php

require 'lib/Autoloader.php';

$config = include 'lib/Config.php';

$bot = new Alyosha($config);
$bot->run();