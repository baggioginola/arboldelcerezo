<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 26/ene/2017
 * Time: 21:58
 */

/**
 * Class Encryption
 */
class Encryption
{
    private static $object = null;
    private static $salt = '4rb01';

    /**
     * @return Encryption|null
     */
    public static function singleton()
    {
        if (!isset(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function setSalt()
    {
        return $this->generateSalt();
    }

    private function generateSalt()
    {
        return base64_encode(round(microtime(true) * 1000) . rand());
    }

    public function generateToken($name = null, $last_name = null)
    {
        if(is_null($name) || is_null($last_name) ){
            return false;
        }

        return base64_encode(self::$salt . $name . $last_name);
    }


}