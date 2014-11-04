<?php

/**
 * Class Event: *wink* symfony/EventDispatcher/Event.php
 */
abstract class Event
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
		return $haltSignal;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $name;
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

