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
    public function getCommand();

    public function getCommandName();

    public function getServer();
}
