<?php
/**
 * Created by PhpStorm.
 * User: ramon.buzo
 * Date: 28/01/2016
 * Time: 15:49
 */

class Debugger
{
    private static $debug = array();

    public static function add($key = '', $data = '', $asArray = false, $line = 0, $method = '')
    {
        if (empty($key)) {
            return false;
        }

        if (empty($line) and empty($method)) {
            if (is_bool($asArray) and $asArray == true) {
                self::$debug[$key][] = $data;
            } else {
                self::$debug[$key] = $data;
            }
        } else {
            if (is_bool($asArray) and $asArray == true) {
                self::$debug[$method][$line][$key][] = $data;
            } else {
                self::$debug[$method][$line][$key] = $data;
            }
        }
   }

    public static function get($setGlobals = true)
    {
        if ($setGlobals) {
            self::_setGlobalVariables();
        }
        return self::$debug;
    }

    private static function _setGlobalVariables()
    {
        if (isset($_SERVER)) {
            foreach ($_SERVER as $key => $value) {
                self::$debug['__server'][strtolower($key)] = $value;
            }
        }

        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                self::$debug['__post'][strtolower($key)] = $value;
            }
        }

        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                self::$debug['__get'][strtolower($key)] = $value;
            }
        }

        if (isset($_COOKIE)) {
            foreach ($_COOKIE as $key => $value) {
                self::$debug['__cookie'][strtolower($key)] = $value;
            }
        }
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                self::$debug['__session'][strtolower($key)] = $value;
            }
        }
    }
}