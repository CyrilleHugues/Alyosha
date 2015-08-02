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
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return trim($this->message);
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
}