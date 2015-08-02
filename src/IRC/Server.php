<?php

namespace Alyosha\IRC;

use Alyosha\IRC\Event\IrcEventGenerator;
use Alyosha\IRC\Message\IrcMessage;
use Alyosha\IRC\Message\IrcProtocolParser;

class Server
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $nickname;

    /**
     * @var array
     */
    protected $chans;

    /**
     * @var IrcProtocolParser
     */
    protected $parser;

    /**
     * @var IrcEventGenerator
     */
    protected $ircEventGenerator;

    /**
     * @param string $name
     * @param array $serverConfig
     */
    public function __construct($name, array $serverConfig)
    {
        $this->name = $name;
        $this->nickname = $serverConfig['nickname'];
        $this->chans = $serverConfig['chans'];

        $address = $serverConfig['address'];
        $port = $serverConfig['port'];
        $this->connection = new Connection($address, $port);
        $this->parser = new IrcProtocolParser($name);
        $this->ircEventGenerator = new IrcEventGenerator();
    }

    public function getEvents()
    {
        $input = $this->connection->receive();
        if (empty($input))
            return [];

        $ircMessage = $this->parser->parse($input);
        if (null !== $ircMessage && !$this->isInternalEvent($ircMessage))
            return $this->ircEventGenerator->generate($this->nickname, $ircMessage);
        return [];
    }

    protected function isInternalEvent(IrcMessage $ircMessage)
    {
        $internalEvent = false;
        switch($ircMessage->getType()){
            case IrcMessage::PING:
                $this->connection->send('PONG '.$ircMessage->getMessage());
                $internalEvent = true;
                break;
            case IrcMessage::IDEN:
                $this->connection->send("USER ".$this->nickname." . . :".$this->nickname);
                $this->connection->send("NICK ".$this->nickname);
                $internalEvent = true;
                break;
            case IrcMessage::MOTD:
                foreach ($this->chans as $chan) {
                    $this->connection->send('JOIN #'.$chan);
                }
                $internalEvent = true;
        }

        return $internalEvent;
    }

    public function execute($command)
    {
        $this->connection->send($command);
    }
} 