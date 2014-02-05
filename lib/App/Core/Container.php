<?php

namespace App\Core;

use Exception;

class Container
{
    /**
     * The unique instance of this class
     * @var Container
     */
    private static $instance;
    
    /**
     * Array contenant les chans avec les pseudonymes des utilisateurs présents
     * @var array
     */
    private $chans = array();
    
    /**
     * Array contenant les hosts complets des utilisateurs authentifiés auprès du bot
     * @var array
     */
    private $ops = array();
    
    /**
     * hostname complet de l'administrateur du bot
     * @var string
     */
    private $admin = "";
    
    /**
     * Ensemble des plugins à charger lors du démarrage du bot
     * @var array
     */
    public $plugins = array();


    private function __construct() {
        foreach (\Config::$cfg['plugins'] as $pluginName) {
            $className = "\\Plugins\\".$pluginName;
            $this->plugins[] = new $className();
        };
    }

    public static function getInstance()
    {
        if (self::$instance !== null)
        {
            return self::$instance;
        }
        else
        {
            self::$instance = new Container();
            return self::$instance;
        }
    }
    
    /**
     * Retourne les informations à savoir sur un message
     */
    public static function extractMsgInfo($input)
    {
        $out = array();
        $input = implode(' ', $input);
        $dbldot = explode(':', $input);
        $infos = explode(' ', $dbldot[1]);
        $user = explode('!', $infos[0]);
        $out['who'] = trim($user[0]);
        $out['hostname'] = trim($user[1]);
        $out['what'] = trim($infos[1]);
        $out['where'] = trim($infos[2]);
        if (count($infos)>3)
            $out['cible'] = trim($infos[3]);
        $out['message'] = trim($dbldot[2]);
        return $out;
    }

    /**
     * getters et setters des variables membres réalisé par flemme.
     */
    public function get($name) 
    {
        $members = get_class_vars("App\Core\Container");
        if (array_key_exists($name, $members))
        {
            return $this->$name;
        }
        else 
        {
            throw new Exception("$name is not a class member.");
        }
    }
    
    public function set($name, $value)
    {
        $members = get_class_vars("App\Core\Container");
        if (array_key_exists($name, $members))
        {
            $this->$name = $value;
        }
        else 
        {
            throw new Exception("$name is not a class member.");
        }
    }
    
    /**
     * Ajout d'un utilisateur sur un chan
     * @param string $username
     * @param string $chan
     * @return null
     */
    public function addUserOnChan($username, $chan)
    {
        if (array_key_exists($chan, $this->chans) && in_array($username, $this->chans[$chan]))
        {
            return;
        }
        $this->chans[$chan][] = $username;
    }
    
    /**
     * Suppression d'un utilisateur d'un chan
     * @param string $username
     * @param string $chan
     */
    public function deleteUserOnChan($username, $chan)
    {
        if (array_key_exists($chan, $this->chans))
        {
            if (in_array($username, $this->chans[$chan]))
            {
                $keys = array_keys($this->chans[$chan],$username, true);
                foreach ($keys as $key)
                {
                    unset($this->chans[$chan][$key]);
                }
            }
        }
    }
    
    /**
     * Recupère les utilisateurs d'un chan
     * @param string $chan
     * @return array
     */
    public function getUsersOnChan($chan)
    {
        if (array_key_exists($chan, $this->chans))
        {
            return $this->chans[$chan];
        }
        else
        {
            return array();
        }
    }

    public function clearUsersOnChan($chan)
    {
        if (array_key_exists($chan, $this->chans))
        {
            unset($this->chans[$chan]);
            $this->chans[$chan] = array();
        }
        else
        {
            $this->chans[$chan] = array();
        }
    }


    /**
     * Ajoute un utilisateur avancé du bot
     * @param type $hostname
     */
    public function addOp($hostname)
    {
        if (!in_array($hostname, $this->ops))
        {
            $this->ops[] = $hostname;
        }
    }
    
    /**
     * Supprime un utilisateur avancé du bot
     * @param type $hostname
     */
    public function deleteOp($hostname)
    {
        if (in_array($hostname, $this->ops))
        {
            $keys = array_keys($this->ops, $hostname, true);
            foreach ($keys as $key)
            {
                unset($this->ops[$key]);
            }
        }
    }
}