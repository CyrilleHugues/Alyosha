<?php

namespace Alyosha;

use Alyosha\Core\Container;

class Alyosha
{
	private $container;

	public function __construct()
	{
		$this->container = Container::getInstance();
	}

	public function run()
	{
		$this->container->fire();
		while (!$this->container->willHalt()) {
			$this->container->beat();
		}

	}
}
