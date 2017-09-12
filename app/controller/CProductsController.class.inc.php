<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 7/18/2016
 * Time: 5:02 PM
 */

require_once 'CBaseController.class.inc.php';
require_once __MODEL__ . 'CProductsModel.class.inc.php';

class Products extends BaseController
{
    private static $object = null;

    private $parameters = array();

    private $validParameters = array(
        'id_producto' => TYPE_INT,
        'id_categoria' => TYPE_INT,
    );

    /**
     * @return null|Products
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    /**
     * @return string
     */
    public function getAll()
    {
        if ($result = ProductsModel::singleton()->getAll()) {
            return $result;
        }
        return false;
    }

    public function getRandomAll()
    {
        if ($result = ProductsModel::singleton()->getRandomAll()) {
            return $result;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getById($parameters)
    {
        $array = array();
        if (!$this->_setParameters($parameters)) {
            return false;
        }

        if ($result = ProductsModel::singleton()->getById($this->parameters['id_producto'])) {

            $price = $this->getPrice($result);

            $total = $price + ($price * $result['iva']);
            $result['precio'] = number_format($total, 2);
            return $result;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getByCategory($parameters)
    {
        if (!$this->_setParameters($parameters)) {
            return false;
        }

        if (!$result = ProductsModel::singleton()->getByCategory($this->parameters['id_categoria'])) {
            return false;
        }

        foreach ($result as $key => $value) {
            $result[$key]['like'] = Like::singleton()->getByIdProduct($value['id_producto']);

            $price = $this->getPrice($value);
            $total = $price + ($price * $value['iva']);
            $result[$key]['precio'] = number_format($total, 2);
        }

        return $result;
    }

    private function getPrice($array = array())
    {
        if (!$array) {
            return false;
        }

        $total = $array['precio'] * $array['tipo_cambio'];
        $total = $total - ($total * ($array['descuento'] / 100));

        return $total;
    }

    public function updateLikes($id_product = null)
    {
        if (is_null($id_product)) {
            return false;
        }

        return ProductsModel::singleton()->updateLikes($id_product);
    }

    /**
     * @return bool
     */
    private function _setParameters($parameters)
    {
        if (!isset($parameters) || empty($parameters)) {
            return false;
        }

        if (!$this->validateParameters($parameters, $this->validParameters)) {
            return false;
        }

        foreach ($parameters as $key => $value) {
            $this->parameters[$key] = $value;
        }

        return true;
    }

    public function __destruct()
    {

    }
}