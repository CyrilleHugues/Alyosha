<?php

namespace Alyosha;

use Alyosha\Core\EventDispatcher;
use Alyosha\Modules\IRC\IrcModule;
use Alyosha\Modules\BotAdmin\BotAdminModule;
use Alyosha\Modules\IrcSecurity\IrcSecurityModule;

class Alyosha
{
	private $ed;

	public function __construct()
    {
        $modules = [
            new IrcModule(),
            new IrcSecurityModule,
            new BotAdminModule(),
        ];
		$this->ed = new EventDispatcher($modules);
	}

	public function run()
    {
        print "Starting the bot ...\n";
		$this->ed->fireModules();
		while (!$this->ed->willHalt()) {
			$this->ed->beat();
            // Wait to lessen cpu charge
            usleep(50000);
		}

	}
}
