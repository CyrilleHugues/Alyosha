<?php

namespace Alyosha\Modules\IRC;

class Config
{
	public static $cfg = array(
        'server'    =>  'irc.iiens.net',
        'port'      =>  6667,
        'nickname'  =>  'Alyosha',
        'adminPassword' => 'federer',
        'chans'     =>  array(
			"#pulco"
        )
    );

    public static function getParam($name, $default = null)
    {
    	if (array_key_exists($name, self::$cfg)) {
    		return self::$cfg[$name];
    	}
    	return $default;
    }
}
