<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 16/ene/2017
 * Time: 21:13
 */
require_once __CONTROLLER__ . 'CCartProductsController.class.inc.php';
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __CONTROLLER__ . 'CImagesController.class.inc.php';
require_once __MODEL__ . 'CCartModel.class.inc.php';

class Cart extends BaseController
{
    private static $object = null;

    private $parameters = array();

    private $validParameters = array(
        'id_producto' => TYPE_INT
    );

    /**
     * @return Cart|null
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function getAllProducts()
    {
        $session_id = session_id();
        if (!$result = CartModel::singleton()->getBySessionId($session_id)) {
            return false;
        }

        if (!$result = CartModel::singleton()->getById($result['id'])) {
            return false;
        }


        return $result;
    }

    /**
     * @return string
     */
    public function getAll()
    {
        $session_id = session_id();
        if (!$result = CartModel::singleton()->getBySessionId($session_id)) {
            return false;
        }

        if (!$total_products = CartModel::singleton()->getTotalProductsById($result['id'])) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return $total_products;
    }

    /**
     * @return string
     */
    public function add()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $session_id = session_id();
        $id = null;

        if (!$result = CartModel::singleton()->getBySessionId($session_id)) {
            if (!$id = CartModel::singleton()->add(array('variable_session' => $session_id, 'fecha' => date('Y-m-d H:i:s')))) {
                return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
            }
            $this->parameters['id_cart'] = $id;
        } else {
            $this->parameters['id_cart'] = $result['id'];
        }

        if (!CartProducts::singleton()->add($this->parameters)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$total_products = CartModel::singleton()->getTotalProductsById($this->parameters['id_cart'])) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse(STATUS_SUCCESS, MESSAGE_SUCCESS, $total_products));
    }

    public function delete()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $session_id = session_id();

        if (!$result = CartModel::singleton()->getBySessionId($session_id)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$result_cart = CartModel::singleton()->getByCartIdProductId($result['id'], $this->parameters['id_producto'])) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!CartModel::singleton()->delete($result_cart['id'])) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse(STATUS_SUCCESS, MESSAGE_SUCCESS));
    }

    /**
     * @return bool
     */
    private function _setParameters()
    {
        if (!isset($_POST) || empty($_POST)) {
            return false;
        }

        if (!$this->validateParameters($_POST, $this->validParameters)) {
            return false;
        }

        foreach ($_POST as $key => $value) {
            $this->parameters[$key] = $value;
        }

        return true;
    }
}