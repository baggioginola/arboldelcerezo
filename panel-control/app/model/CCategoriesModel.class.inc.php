<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 5/12/2016
 * Time: 9:53 AM
 */
require_once CLASSES . 'CDatabase.class.inc.php';

class CategoriesModel extends Database
{
    private static $object = null;
    private static $table = 'categorias';
    private static $key = 'id_categoria';

    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    /**
     * @return array|bool
     */
    public function getAll()
    {
        if (!$this->connect()) {
            return false;
        }
        $result_array = array();

        $query = "SELECT id_categoria,categorias.nombre, categoria_padre.nombre as tipo FROM "
            . self::$table .
            " LEFT JOIN categoria_padre
            ON categorias.id_categoria_padre = categoria_padre.id_categoria_padre
            WHERE categorias.status = true
            ORDER BY categorias.nombre ASC";

        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }

    public function getLastId()
    {
        if (!$this->connect()) {
            return false;
        }
        $result_array = array();

        $query = "SELECT MAX(id_categoria) as id FROM " . self::$table . " ";

        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        return $result_array;
    }

    public function getById($id = '')
    {
        if (empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT id_categoria,nombre,id_categoria_padre FROM " . self::$table . " WHERE id_categoria = '" . $id . "' ";

        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        return $result_array;
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

        $user_id = $this->getLastId();

        $this->close_connection();

        return $user_id;
    }

    /**
     * @param array $data
     * @return bool|int|string
     */
    public function edit($data = array(), $id = '')
    {
        if (empty($data) || empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        if (!$result = $this->update($data, $id, self::$table, self::$key)) {
            return false;
        }

        $this->close_connection();

        return $result;
    }
}