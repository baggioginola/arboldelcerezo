<?php
/**
 * Created by PhpStorm.
 * User: ramon
 * Date: 11/02/16
 * Time: 03:56 PM
 */

require_once CLASSES . 'CDatabase.class.inc.php';

class CLogsModel extends Database
{
    private static $object = null;
    private static $table = 'logs';

    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function add($data)
    {
        if (empty($data)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        if (!$this->insert($data, self::$table)) {
            return false;
        }

        $this->close_connection();

        return true;
    }
}