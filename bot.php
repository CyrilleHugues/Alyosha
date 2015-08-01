<?php
require "vendor/autoload.php";

use Alyosha\Alyosha;

proc_nice(20);

$bot = new Alyosha();
$bot->run();

