<?php

use Alyosha\IRC\IrcModule;
use Alyosha\Time\TimeModule;
use Alyosha\IrcAdmin\IrcAdminModule;

require_once __DIR__.'/vendor/autoload.php';

class AppKernel extends Alyosha\Core\Kernel
{
    protected function registerModules()
    {
        return [
            new IrcModule(),
            new TimeModule(),
            new IrcAdminModule(),
        ];
    }
}

$kernel = new AppKernel(__DIR__.'/config.yml');
$kernel->run();

