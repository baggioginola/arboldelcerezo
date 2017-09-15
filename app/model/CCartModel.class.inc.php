<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 16/ene/2017
 * Time: 21:15
 */
require_once CLASSES . 'CDatabase.class.inc.php';

class CartModel extends Database
{
    private static $object = null;
    private static $table = 'cart';

    /**
     * @return CartModel|null
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

        $query = "SELECT id FROM " . self::$table . " WHERE variable_session = '" . $id . "' ";

        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        return $result_array;
    }


    public function getByCartIdProductId($cart_id = '', $product_id = '')
    {
        if (empty($cart_id) || empty($product_id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = "SELECT id_cart_productos FROM cart_productos WHERE id_cart = '" . $cart_id . "'
                    AND id_producto = '" . $product_id . "' ";

        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array = $row;
        }

        return $result_array;
    }

    public function getTotalProductsById($id = '')
    {
        if (empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $result_array = array();

        $query = 'SELECT COUNT(cart_productos.id) as total
        FROM cart INNER JOIN cart_productos
        ON cart.id = cart_productos.id_cart
        WHERE id_cart = "' . $id . '" ';

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
		productos.id_producto,iva,marcas.nombre as marca, categorias.nombre as categoria,
		tipo_cambio.moneda,marcas.descuento,productos.id_marca, tipo_cambio.tipo_cambio, precio, productos.codigo_interno
		FROM  cart inner join cart_productos
		ON cart.id_cart = cart_productos.id_cart
		INNER JOIN productos
		ON cart_productos.id_producto = productos.id_producto
		INNER JOIN marcas
		ON marcas.id_marca = productos.id_marca
		INNER JOIN categorias
		ON categorias.id_categoria = productos.id_categoria
		INNER JOIN tipo_cambio
		ON productos.moneda = tipo_cambio.id_tipo_cambio
		WHERE 1=1 AND cart.id_cart ='" . $id . "'
		GROUP BY id_producto;";

        #echo $query . "\n";

        if (!$result = $this->query($query)) {
            return false;
        }

        $this->close_connection();

        while ($row = $this->fetch_assoc($result)) {
            $result_array[] = $row;
        }

        return $result_array;
    }

    public function delete($id = '')
    {
        if (empty($id)) {
            return false;
        }

        if (!$this->connect()) {
            return false;
        }

        $where = "id_cart_productos = " . $id;

        if (!$result = $this->remove('cart_productos', $where)) {
            return false;
        }

        $this->close_connection();

        return true;
    }


}