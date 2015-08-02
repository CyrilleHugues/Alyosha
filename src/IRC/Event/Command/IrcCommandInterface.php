<?php
/**
 * Created by PhpStorm.
 * User: cyrille
 * Date: 02/08/15
 * Time: 12:34
 */

namespace Alyosha\IRC\Event\Command;


interface IrcCommandInterface
{
    /**
     * @return string
     */
    public function getCommand();

    /**
     * @return string
     */
    public function getCommandName();

    /**
     * @return string
     */
    public function getServer();
}
