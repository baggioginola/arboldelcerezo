<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 19/ene/2017
 * Time: 23:28
 */

require_once CLASSES . 'CDatabase.class.inc.php';

class PaypalModel extends Database
{
    private static $object = null;
    private static $table = 'cart';

    /**
     * @return PaypalModel|null
     */
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

        $query = "SELECT id_categoria,nombre FROM " . self::$table . " WHERE status = true";

        if (!$result = $this->query($query)) {
            return false;
        }

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
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

        $id = $this->getLastId();

        $this->close_connection();

        return $id;
    }


    public function getBySessionId($id = '')
    {
        if (empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT id_cart FROM " . self::$table . " WHERE variable_sesion = '" . $id . "' ";

        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

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

        $query = "SELECT cart_productos.id_cart_productos,cart.id_cart as id_cart,
		productos.nombre, SUM(precio) as total, COUNT(productos.id_producto) AS numero_productos, 
		productos.id_producto,iva,
		tipo_cambio.moneda,marcas.descuento,productos.id_marca, tipo_cambio.tipo_cambio, precio
		FROM  cart inner join cart_productos
		ON cart.id_cart = cart_productos.id_cart
		INNER JOIN productos
		ON cart_productos.id_producto = productos.id_producto
		INNER JOIN marcas
		ON marcas.id_marca = productos.id_marca
		INNER JOIN tipo_cambio
		ON productos.moneda = tipo_cambio.id_tipo_cambio
		WHERE 1=1 AND cart.id_cart ='" . $id . "'
		GROUP BY id_producto;";

        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }
}