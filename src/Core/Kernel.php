<?php

namespace Alyosha\Core;

use Alyosha\Config\Parser;
use Alyosha\Core\EventDispatcher\Dispatcher;
use Alyosha\Core\Module\ModuleInterface;

abstract class Kernel
{
    /**
     * @var ModuleInterface[]
     */
    protected $modules;

    /**
     * @var array
     */
    protected $subscribers = [];

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    public function __construct($configFileName)
    {
        /** @var array $config */
        $parser = new Parser($configFileName);
        $config = $parser->getConfig();
        $this->setUpModules($config);
    }

    /**
     * Will return all the modules registered by the application
     *
     * @return ModuleInterface[]
     */
    protected abstract function registerModules();

    /**
     * @param $config
     */
    protected function setUpModules($config)
    {
        $modules = $this->registerModules();
        foreach ($modules as $module) {
            $module->start($config);
        }
        $this->dispatcher = new Dispatcher();
        $this->dispatcher->setListeners($modules);
    }

    public function run()
    {
		while (!$this->dispatcher->shouldHalt) {
			$this->dispatcher->beat();
            // Wait to lessen cpu charge
            usleep(50000);
		}
	}
}
