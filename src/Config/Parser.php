<?php

namespace Alyosha\Config;

use Symfony\Component\Yaml\Yaml;

class Parser
{
    /** @var array */
    protected $config;

    /**
     * @param $filename
     *
     * @throws \Exception
     */
    public function __construct($filename)
    {
        if (trim($filename) == '' || !file_exists($filename)){
            throw new \Exception('Configuration file is not configured correctly');
        }

        $this->config = Yaml::parse($filename);
    }

    public function getConfig()
    {
        return $this->config;
    }
}
