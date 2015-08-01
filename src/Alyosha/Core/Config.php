<?php

namespace Alyosha\Core;

class Config
{
	public static $cfg = [
        'server'    =>  'irc.iiens.net',
        'port'      =>  6667,
        'nickname'  =>  'Alyosha',
        'adminPassword' => 'malcom',
        'chans'     =>  [
			"#neet"
        ]
    ];

    public static function generate()
    {
        $fileContent = file_get_contents(__DIR__."/../parameters.json");
        $config = json_decode($fileContent, true);

        return $config;
    }
}
