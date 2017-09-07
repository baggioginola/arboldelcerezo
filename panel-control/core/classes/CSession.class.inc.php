<?php

/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 9/23/2016
 * Time: 9:22 AM
 */

require_once CLASSES . 'CEncryption.class.inc.php';

/**
 * Class Session
 */
class Session
{
    private static $object = null;

    /**
     * @return null|Session
     */
    public static function singleton()
    {
        if (!isset(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function store($token)
    {
        $_SESSION['token'] = $token;
    }

    public function storeUserInfo($name = '', $last_name = '')
    {
        $_SESSION['name'] = $name;
        $_SESSION['last_name'] = $last_name;
    }

    public function destroy()
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }

        if (isset($_SESSION['name'])) {
            unset($_SESSION['name']);
        }

        if (isset($_SESSION['last_name'])) {
            unset($_SESSION['last_name']);
        }

        unset($_SESSION['token']);
        return true;
    }

    public function validate()
    {
        if (!$token = $this->getToken()) {
            return false;
        }

        if (!$this->getArgs()) {
            return false;
        }

        list($name, $last_name) = $this->getArgs();

        if (!($token == Encryption::singleton()->generateToken($name, $last_name))) {
            return false;
        }

        return true;
    }

    private function getArgs()
    {
        if (!isset($_SESSION['name']) || !isset($_SESSION['last_name'])) {
            return false;
        }

        return array($_SESSION['name'], $_SESSION['last_name']);
    }

    public function getName()
    {
        if(isset($_SESSION['name'])) {
            return $_SESSION['name'];
        }
        return '';
    }

    public function getLastName()
    {
        if(isset($_SESSION['last_name'])) {
            return $_SESSION['last_name'];
        }
        return '';
    }
    private function getToken()
    {
        if (!isset($_SESSION['token'])) {
            return false;
        }
        return $_SESSION['token'];
    }
}