<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 15/ene/2017
 * Time: 19:05
 */
require_once CLASSES . 'CDatabase.class.inc.php';

class SearchModel extends Database
{
    private static $object = null;
    private static $table = 'producto';

    /**
     * @return null|SearchModel
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }


    public function getProductsByQuery($query = null)
    {
        if (is_null($query)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT " . self::$table . ".id, " . self::$table . ".id_categoria, " . self::$table . ".nombre,
        " . self::$table . ".descripcion,
        " . self::$table . ".precio,
        categoria.nombre as categoria
            FROM  " . self::$table . "
            LEFT JOIN categoria
             ON " . self::$table . ".id_categoria = categoria.id
            WHERE " . self::$table . ".nombre LIKE '%" . $query . "%'
            AND " . self::$table . ".STATUS = true AND categoria.status = true";

        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }
}