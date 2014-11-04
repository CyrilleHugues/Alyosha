<?php

namespace Alyosha\Modules\IRC;

class IrcModule
{
	private $connexion;
	private $events = [];

	public function __construct()
	{
		$this->connexion = Connexion::getInstance();
	}

	public function fire()
	{
		$this->connexion->fire();
	}

	public function getTriggers()
	{
		return [
			"irc.publish" => "sendMessage",
			"irc.quit" => "quit",
			"irc.kick" => "kick",
			"irc.join" => "join",
		];
	}

	public function notify(Event $event){}

	public function getEvents()
	{
		$input = $this->connexion->receive();
		$event = $this->extractEvent($input);
		// ALWAYS do your own treatment before notifying other modules
		$this->preTreatment($input);
		$events = $this->generateEvents();
		return $events;
	}

	public function extractEvent($input)
	{
		$event = new Event();
		$spaces = explode(' ', $input);
		if ($spaces[0] == "PING") {
			$event->setAction("ping");
			$event->setMessage($spaces[2]);
		} elseif (count($input)>=5 && $input[4]=="Found") {
			$event->setAction("ident");
		} else (count($spaces) >= 2) {
			switch ($input[1]) {
				// Fin du MOTD
			case '376':
				$event->setAction("motd_end");
				break;

				// Affichage du topic
			case '332':
				$event->setAction("motd_end");
				$event->setMessage($spaces[2]);
				break;

				// Affichage de la date et de l'auteur de la derniere modification du topic
			case '333':
				$events[] = $this->pluginName."/DISPLAY_TOPIC_INFO";
				break;

				// Affichage des users sur le chan
			case '353':
				$events[] = $this->pluginName."/NAMES";
				break;

				// Fin d'affichage des users sur le chan
			case '366':
				$events[] = $this->pluginName."/NAMES_END";
				break;

				// Join d'un user sur le chan
			case 'JOIN':
				$events[] = $this->pluginName."/JOIN";
				break;

				// Depart d'un user du chan
			case 'PART':
				$events[] = $this->pluginName."/PART";
				break;

				// Kick d'un user d'un chan
			case 'KICK':
				$events[] = $this->pluginName."/KICK";
				break;

				// message
			case 'PRIVMSG':
				$events[] = $this->pluginName."/PRIVMSG";
				$infos = Container::extractMsgInfo($input);
				if ($infos['where'] == \Config::$cfg['nickname'])
				{
					$events[] = $this->pluginName."/QUERY";
					if ($infos['hostname'] == Container::getInstance()->get('admin'))
					{
						$events[] = $this->pluginName."/SECURED_QUERY";
					}
				}
				else
				{
					$events[] = $this->pluginName."/PUBLIC";
					if (preg_match('/'.\Config::$cfg['nickname'].'/', $infos['message']) == 1)
					{
						$events[] = $this->pluginName."/HILIGHT";
					}
				}
				break;

			case 'KICK':
				$events[] = $this->pluginName."/KICK";
				$infos = Container::extractMsgInfo($input);
				if ($infos['cible'] == \Config::$cfg['nickname']){
					$events[] = $this->pluginName."/WAS_KICKED";
				}
				break;
			case 'INVITE':
				$events[] = $this->pluginName."/INVITE";
				$infos = Container::extractMsgInfo($input);
				if ($infos['where'] == \Config::$cfg['nickname']){
					$events[] = $this->pluginName."/WAS_INVITED";
				}
				break;
			}
		}

		echo implode(" ", $input);
	}

	private function preTreatment(array $input)
	{
		if ($input[0] == "PING") {
			$this->connexion->send([["PONG", $input[1]]]);
		}
		if (count($input)>=5 && $input[4]=="Found") {
			$username = Config::getParam('nickname', "Tanche_".rand());

			$this->connexion->send([
				["NICK", $username],
				["USER","$username localhost.com $username :$username"],
			]);
		}
	}
}

