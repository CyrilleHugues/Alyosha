<?php

namespace Alyosha\Core;

class Event implements EventInterface
{
	protected $propagationStopped = false;

	protected $name;

	protected $haltSignal = false;

	public function sendHaltSignal()
	{
		$this->haltSignal = true;
	}

	public function isHaltSignal()
	{
		return $this->haltSignal;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function stopPropagation()
	{
		$this->propagationStopped = true;
	}

	public function isPropagationStopped()
	{
		return $this->propagationStopped;
	}
}

