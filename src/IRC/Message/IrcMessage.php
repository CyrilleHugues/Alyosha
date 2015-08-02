<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 02/08/15
 * Time: 03:14
 */

namespace Alyosha\IRC\Message;


class IrcMessage
{
    const PING = 'ping';
    const IDEN = 'ident';
    const MOTD = 'motd_end';
    const JOIN = 'join';
    const PART = 'part';
    const MESG = 'message';
    const KICK = 'kick';
    const INVT = 'invite';
    const UNKW = 'unknown';

    protected $type;
    protected $username;
    protected $hostname;
    protected $channel;
    protected $target;
    protected $message;
    protected $server;

    /**
     * @param string $type
     * @param string $username
     * @param string $hostname
     * @param string $channel
     * @param string $message
     * @param string $target
     */
    public function __construct(
        $type = null,
        $username = null,
        $hostname = null,
        $channel = null,
        $message = null,
        $target = null
    ) {
        $this->type = $type;
        $this->username = $username;
        $this->hostname = $hostname;
        $this->channel = $channel;
        $this->target = $target;
        $this->message = $message;
    }

    /**
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return trim($this->message);
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
