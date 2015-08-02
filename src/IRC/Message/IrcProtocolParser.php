<?php

namespace Alyosha\IRC\Message;

class IrcProtocolParser
{
    protected $serverName;

    /**
     * @param string $serverName
     */
    public function __construct($serverName)
    {
        $this->serverName = $serverName;
    }

    /**
     * @param string $input
     *
     * @return IrcMessage
     */
    public function parse($input)
    {
        $ircMessage = $this->createIrcMessage($input);
        if (null !== $ircMessage)
            $ircMessage->setServer($this->serverName);

        return $ircMessage;
    }

    /**
     * @param $input
     *
     * @return IrcMessage
     */
    protected function createIrcMessage($input)
    {
        $ircMessage = null;
        $spaces = explode(' ', $input);
        if ($spaces[0] === 'PING') {
            return new IrcMessage(IrcMessage::PING, null, null, null, $spaces[1]);
        }
        if (count($spaces)>=5 && $spaces[4]=="Checking") {
            return new IrcMessage(IrcMessage::IDEN);
        }
        if (count($spaces) >= 2) {
            $user = substr($spaces[0], 1);
            $user = explode('!', $user);
            switch ($spaces[1]) {
                // Fin du MOTD - time to join channels
                case '376':
                    $ircMessage = new IrcMessage(IrcMessage::MOTD);
                    break;
                // Join d'un user sur le chan
                case 'JOIN':
                    $ircMessage = new IrcMessage(IrcMessage::JOIN, $user[0], $user[1], $spaces[2]);
                    break;

                // Depart d'un user du chan
                case 'PART':
                    $message = '';
                    if (count($spaces) > 3)
                        $message = implode(" ", array_slice($spaces, 3));
                    $ircMessage = new IrcMessage(IrcMessage::PART, $user[0], $user[1], $spaces[2], $message);
                    break;

                // message
                case 'PRIVMSG':
                    $message = substr(implode(" ", array_slice($spaces, 3)), 1);
                    $ircMessage = new IrcMessage(IrcMessage::MESG, $user[0], $user[1], $spaces[2], $message);
                    break;

                case 'KICK':
                    $message = substr(implode(" ", array_slice($spaces, 4)), 1);
                    $ircMessage = new IrcMessage(IrcMessage::KICK, $user[0], $user[1], $spaces[2], $message, $spaces[3]);
                    break;

                case 'INVITE':
                    $ircMessage = new IrcMessage(
                        IrcMessage::INVT,
                        $user[0],
                        $user[1],
                        substr($spaces[3], 1),
                        null,
                        $spaces[2]
                    );
            }
        }

        return $ircMessage;
    }
}
