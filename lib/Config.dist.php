<?php

class Config {
    public static $cfg = array(
        'server'    =>  'irc.iiens.net',
        'port'      =>  6667,
        'nickname'  =>  'Alyosha',
        'adminPassword' => 'TheCakeMayHappen',
        'chans'     =>  array(
                            "#neet"
                        ),
        'plugins'   =>  array(
                            'Required\Core',
                            'Required\Security',
                            'Required\PluginWorker',
                            'Required\Channel',
                            'Required\Controls',
                            'Required\Logger',
                            'MyPlugins\Houhou'
                        )
    );
}
