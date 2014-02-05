<?php

namespace Plugins\Required;

use App\Plugin\Plugin;
use App\Core\Container;

class PluginWorker extends Plugin
{
    public $pluginName="PluginWorker";
    protected $triggers = array(
        "PluginWorker/LOAD" => "callBackLoad",
        "PluginWorker/UNLOAD" => "callBackUnload"
    );
    
    public function getEvents(array $input) {
        $events = array();
        
        if (count($input)>=2 && $input[1] == "PRIVMSG")
        {
            $infos = Container::extractMsgInfo($input);
            $message = explode(' ', $infos['message']);
            // si c'est une query
            if ($infos['where'] == \Config::$cfg['nickname'] && count($message)>=2)
            {
                if ('!load' == trim($message[0]))
                {
                    $events[] = $this->pluginName."/LOAD";
                }

                if ('!unload' == trim($message[0]))
                {
                    $events[] = $this->pluginName."/UNLOAD";
                }
            }
        }
        return $events;
    }
    
    public function callBackLoad($input)
    {
        $infos = Container::extractMsgInfo($input);
        $message = explode(' ', $infos['message']);
        $module = trim($message[1]);
        $ans = array();
        
        if ($infos['hostname'] == Container::getInstance()->get('admin'))
        {
            $className = "\\Plugins\\".$module;
            if (class_exists($className)){
                $alreadyLoaded = false;
                $arModule = explode("\\", $module);
                $classy = $arModule[count($arModule)-1];
                foreach (Container::getInstance()->plugins as $plugin) {
                    if ($plugin->pluginName == $classy)
                        $alreadyLoaded = true;
                }
                if ($alreadyLoaded == FALSE){
                    Container::getInstance()->plugins[] = new $className();
                    $ans[] = array('PRIVMSG', $infos['who']." :Le plugin $module a été load.");
                }
                else{
                    $ans[] = array('PRIVMSG', $infos['who']." :Le plugin $module est déjà load.");
                }
            }
        }
        return $ans;
    }
    
    public function callBackUnload($input)
    {
        $infos = Container::extractMsgInfo($input);
        $message = explode(' ', $infos['message']);
        $pluginName = trim($message[1]);
        $ans = array();
        if ($infos['hostname'] == Container::getInstance()->get('admin'))
        {
            foreach (Container::getInstance()->plugins as $plug) {
                if ($plug->pluginName == $pluginName)
                {
                    $key = array_search($plug, Container::getInstance()->plugins);
                    if ($key != FALSE)
                    {
                        unset(Container::getInstance()->plugins[$key]);
                        $ans[] = array('PRIVMSG',$infos['who']." :Le plugin $pluginName a été unload.");
                    }
                }
            }
        }
        return $ans;
    }
}
