<?php

use Alyosha\IRC\IrcModule;

require_once __DIR__.'/vendor/autoload.php';

class AppKernel extends Alyosha\Core\Kernel
{
    protected function registerModules()
    {
        return [
            new IrcModule()
        ];
    }
}

$kernel = new AppKernel(__DIR__.'/config.yml');
$kernel->run();

