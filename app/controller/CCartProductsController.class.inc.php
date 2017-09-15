<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 16/ene/2017
 * Time: 21:49
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __MODEL__ . 'CCartProductsModel.class.inc.php';
class CartProducts extends BaseController
{
    private static $object = null;

    private $parameters = array();

    private $validParameters = array(
        'id_cart' => TYPE_INT,
        'id_producto' => TYPE_INT
    );

    /**
     * @return CartProducts|null
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
    public function add($parameters)
    {
        if (!$this->_setParameters($parameters)) {
            return false;
        }

        if (!$result = CartProductsModel::singleton()->add($this->parameters)) {
            return false;
        }

        return true;
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
}