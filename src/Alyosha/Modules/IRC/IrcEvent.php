<?php

namespace Alyosha\Modules\IRC;

use Alyosha\Core\Event;

class IrcEvent extends Event
{
    protected $username = null;
	protected $hostname = null;

	/** kick, privmsg, etc... */
	protected $action = null;

	protected $channel = null;

	/** like the target of a kick */
	protected $target = null;

	protected $message = null;

	public function __construct($name = "")
	{
		$this->name = "irc.".$name;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getUsername()
	{
		return $this->username;
	}
	
	public function setHostname($hostname)
	{
		$this->hostname = $hostname;
	}

	public function getHostname()
	{
		return $this->hostname;
	}
	
	public function setAction($action)
	{
		$this->action = $action;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function setChannel($channel)
	{
		$this->channel = $channel;
	}

	public function getChannel()
	{
		return $this->channel;
	}

	public function setTarget($target)
	{
		$this->target = $target;
	}

	public function getTarget()
	{
		return $this->target;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function clone_ev()
	{
		$event = new IrcEvent();
		$event->setMessage($this->message);
		$event->setTarget($this->target);
		$event->setChannel($this->channel);
		$event->setAction($this->action);
		$event->setHostname($this->hostname);
		$event->setUsername($this->username);

		return $event;
	}
}

