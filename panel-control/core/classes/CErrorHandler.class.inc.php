<?php
/**
 * Created by PhpStorm.
 * User: ramon.buzo
 * Date: 28/01/2016
 * Time: 11:12
 */

class ErrorHandler
{
    private static $error = array();

    public static function add($desc, $line, $method, $script)
    {
        self::$error[] = array(
            'desc'      => $desc,
            'line'      => $line,
            'method'    => $method,
            'script'    => $script,
        );
    }

    public static function getErrors()
    {
        return self::$error;
    }
}