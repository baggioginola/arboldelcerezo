<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 26/ene/2017
 * Time: 22:00
 */

require_once CLASSES . 'CDatabase.class.inc.php';

class LoginModel extends Database
{
    private static $object = null;
    private static $table = 'usuario';

    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function authenticate($data = array())
    {
        if (!$data) {
            return false;
        }

        if (!isset($data['e_mail']) || !isset($data['password'])) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT id, nombre, apellidos, password FROM " . self::$table . " WHERE email = '" . $data['e_mail'] . "' AND status = 1 ";

        if (!$result = $this->query($query)) {
            return false;
        }

        if ($result->num_rows == 0) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        if ($result_array['password'] != $data['password']) {
            return false;
        }

        return $result_array;
    }
}