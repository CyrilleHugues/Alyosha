<?php

namespace Alyosha\IRC\Event;

use Alyosha\IRC\Message\IrcMessage;

class IrcEventGenerator
{
    const IS_KICKED = 'is_kicked';
    const IS_INVITED = 'is_kicked';
    const PRIVATE_MESSAGE = 'private_message';
    const PUBLIC_MESSAGE = 'public_message';
    const HILIGHT_MESSAGE = 'hilight_message';

    public function generate($nickname, IrcMessage $ircMessage)
    {
        $events = [];

        switch ($ircMessage->getType()) {
            case IrcMessage::MOTD:
                $ircEvent = new IrcEvent(IrcMessage::MOTD, $ircMessage);
                $events[] = $ircEvent;
                break;
            case IrcMessage::JOIN:
                $ircEvent = new IrcEvent(IrcMessage::JOIN, $ircMessage);
                $events[] = $ircEvent;
                break;
            case IrcMessage::PART:
                $ircEvent = new IrcEvent(IrcMessage::PART, $ircMessage);
                $events[] = $ircEvent;
                break;
            case IrcMessage::MESG:
                if ($ircMessage->getChannel() == $nickname){
                    $ircEvent = new IrcEvent(self::PRIVATE_MESSAGE, $ircMessage);
                } elseif (1 == preg_match('/'.$nickname.'/', $ircMessage->getMessage())) {
                    $ircEvent = new IrcEvent(self::HILIGHT_MESSAGE, $ircMessage);
                } else {
                    $ircEvent = new IrcEvent(self::PUBLIC_MESSAGE, $ircMessage);
                }
                $events[] = $ircEvent;

                break;
            case IrcMessage::KICK:
                $ircEvent = new IrcEvent(IrcMessage::KICK, $ircMessage);
                $events[] = $ircEvent;
                if ($ircMessage->getTarget() == $nickname) {
                    $ircEvent = new IrcEvent(self::IS_KICKED, $ircMessage);
                    $events[] = $ircEvent;
                }
                break;
            case IrcMessage::INVT:
                $ircEvent = new IrcEvent(IrcMessage::INVT, $ircMessage);
                $events[] = $ircEvent;
                if ($ircMessage->getTarget() == $nickname) {
                    $ircEvent = new IrcEvent(self::IS_INVITED, $ircMessage);
                    $events[] = $ircEvent;
                }
        }

        return $events;
    }
}
