<?php

namespace Alyosha\Modules\IRC;

use Alyosha\Core\Event;

class IrcEvent extends Event
{
	private $username;
	private $hostname;

	/** kick, privmsg, etc... */
	private $action;

	private $channel;

	/** like the target of a kick */
	private $target;

	private $message;

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
}
