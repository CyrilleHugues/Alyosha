<?php

class Config {
    public static $cfg = array(
        'server'    =>  'irc.xxx.xxx',
        'port'      =>  6667,
        'nickname'  =>  'xxxxx',
        'adminPassword' => 'xxxxxx',
        'chans'     =>  array(
                            "#xxxx",
                            "#xxxx"
                        ),
        'plugins'   =>  array(
                            'Required\Core',
                            'Required\Security',
                            'Required\PluginWorker',
                            'Required\Channel',
                            'Required\Controls',
                            'Required\Logger',
                        )
    );
}