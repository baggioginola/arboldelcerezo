<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 16/ene/2017
 * Time: 21:53
 */

require_once CLASSES . 'CDatabase.class.inc.php';

class CartProductsModel extends Database
{
    private static $object = null;
    private static $table = 'cart_productos';

    /**
     * @return CartProductsModel|null
     */
    public static function singleton()
    {
        if(is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    /**
     * @param array $data
     * @return bool|int|string
     */
    public function add($data = array())
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