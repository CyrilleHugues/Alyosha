<?php

namespace Alyosha\Modules\IRC;

use Alyosha\Core\Event;

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

	public function notify(Event $event){
        if ($event->getName() == "irc.publish"){
            $this->connexion->send([
                ["PRIVMSG", $event->getChannel()." :".$event->getMessage()]
            ]);
        }
    }

	public function getEvents()
	{
		$input = $this->connexion->receive();
		$event = $this->extractEvent($input);

        if ($event->getAction() === null) return [];
		// ALWAYS do your own treatment before notifying other modules
		$this->preTreatment($event);
		$events = $this->generateEvents($event);
        var_dump($events);
		return $events;
	}

    public function generateEvents(IrcEvent $event)
    {
        $event->setName("irc.".$event->getAction());
        $events = [$event];
        if ($event->getName() == "irc.message") {
            if ($event->getChannel() == Config::getParam("nickname")) {
                $e = $event->clone_ev();
                $e->setName("irc.private_message");
                $events[] = $e;
            } else {
                $e = $event->clone_ev();
                $e->setName("irc.public_message");
                $events[] = $e;
            }
            if (strpos($event->getMessage, Config::getParam('nickname'))) {
                $e = $events[count($events) - 1]->clone_ev();
                $e->setName("irc.hilight");
                $events[] = $e;
            }
        } elseif ($event->getName() == "irc.kick" && $event->getTarget() == Config::getParam("nickname")) {
            $e = $event->clone_ev();
            $e->setName("irc.is_kicked");
            $events[] = $e;
        } elseif ($event->getName() == "irc.invite" && $event->getTarget() == Config::getParam("nickname")) {
            $e = $event->clone_ev();
            $e->setName("irc.is_invited");
            $events[] = $e;
        }

        return $events;
    }

	public function extractEvent($input)
	{
		$event = new IrcEvent();
		$spaces = explode(' ', $input);
		if ($spaces[0] == "PING") {
			$event->setAction("ping");
			$event->setMessage($spaces[1]);
		} elseif (count($spaces)>=5 && $spaces[4]=="Found") {
            // this condition is pretty saddening ... saddening but working :B
			$event->setAction("ident");
		} elseif (count($spaces) >= 2) {
            $user = substr($spaces[0], 1);
            $user = explode('!', $user);
			switch ($spaces[1]) {
				// Fin du MOTD - time to join channels
                case '376':
                    $event->setAction("motd_end");
                    break;
                // Join d'un user sur le chan
                case 'JOIN':
                    $event->setAction("join");
                    $event->setUsername($user[0]);
                    $event->setHostname($user[1]);
                    $event->setChannel($spaces[2]);
                    break;

                // Depart d'un user du chan
                case 'PART':
                    $event->setAction("part");
                    $event->setUsername($user[0]);
                    $event->setHostname($user[1]);
                    $event->setChannel($spaces[2]);
                    if (count($spaces) > 3) {
                        $message = implode(" ", array_slice($spaces, 3));
                        $event->setMessage($message);
                    }
                    break;

                // message
                case 'PRIVMSG':
                    $event->setAction("message");
                    $event->setUsername($user[0]);
                    $event->setHostname($user[1]);
                    $event->setChannel($spaces[2]);
                    $message = implode(" ", array_slice($spaces, 3));
                    $event->setMessage(substr($message, 1));
                    break;

                case 'KICK':
                    $event->setAction("kick");
                    $event->setUsername($user[0]);
                    $event->setHostname($user[1]);
                    $event->setChannel($spaces[2]);
                    $event->setTarget($spaces[3]);
                    $message = implode(" ", array_slice($spaces, 4));
                    $event->setMessage(substr($message, 1));
                    break;

                case 'INVITE':
                    $event->setAction("invite");
                    $event->setUsername($user[0]);
                    $event->setHostname($user[1]);
                    $event->setTarget($spaces[2]);
                    $event->setChannel(substr($spaces[3], 1));
				    break;
                default:
                    $event->setMessage($input);
			}
		}

		return $event;
	}

	private function preTreatment(IrcEvent $event)
	{
        if ($event->getAction() == "ping") {
            $this->connexion->send([["PONG", $event->getMessage()]]);
        }
        if ($event->getAction() == "ident") {
            $username = Config::getParam('nickname', "Tanche_".rand());

            $this->connexion->send([
                ["NICK", $username],
                ["USER","$username localhost.com $username :$username"],
            ]);
        }
        if ($event->getAction() == "motd_end"){
            $this->connexion->send([
                ["JOIN", "#pulco"]
            ]);
        }

        /** @Todo remove this code */
        if ($event->getAction() == "invite" && $event->getTarget() == Config::getParam("nickname")){
            $this->connexion->send([
                ["JOIN", $event->getChannel()]
            ]);
        }
	}
}

