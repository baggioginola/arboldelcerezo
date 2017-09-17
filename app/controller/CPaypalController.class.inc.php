<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 19/ene/2017
 * Time: 23:27
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __CONTROLLER__ . 'CCartProductsController.class.inc.php';
require_once __MODEL__ . 'CCartModel.class.inc.php';

require_once __MODEL__ . 'CPaypalModel.class.inc.php';

/**
 * Class Paypal
 */
class Paypal extends BaseController
{
    private static $object = null;

    private $parameters = array();
    private $iva = 0.16;
    private $email = 'mariocuevas-facilitator88@gmail.com'; //sandobx account
    private $queryString = '';
    private $validParameters = array();
    private $url = array();
    private $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; //sandbox url

    /**
     * @return Paypal|null
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
    public function pay()
    {
        $this->setUrl();

        $session_id = session_id();
        if (!$result = CartModel::singleton()->getBySessionId($session_id)) {
            return false;
        }
        if (!$result_cart = $result = CartModel::singleton()->getById($result['id'])) {
            return false;
        }

        $row_array = array();
        $result_all = array();
        $i = 0;
        $total = 0;
        /*
        foreach ($result_cart as $key) {
            foreach ($key as $value => $result) {
                $row_array[$value] = $result;
            }
            $result_all[$i]['nombre'] = $row_array['codigo_interno'];
            $result_all[$i]['numero_productos'] = $row_array['numero_productos'];

            $price = $this->getPrice($row_array);
            $result_all[$i]['precio'] = number_format($this->getPrice($row_array), 2, '.', '');
            $result_all[$i]['iva'] = number_format(($price * $this->iva), 2, '.', '');
            $i++;
        }
        */

        $this->setQueryString($result_cart);

        $result = $this->paypal_url . $this->queryString;

        return $result;
    }

    private function setUrl()
    {
        $this->url['return'] = DOMAIN;
        $this->url['cancel'] = DOMAIN;
        $this->url['notify'] = DOMAIN;
    }

    private function setQueryString($array = array())
    {
        if (!$array) {
            return false;
        }

        $this->queryString .= "?business=" . urlencode($this->email) . "&cmd=_cart&upload=1&currency_code=MXN&notify_version=2.1&";

        $i = 1;
        foreach ($array as $key => $value) {
            $this->queryString .= "item_name_" . $i . "=" . urlencode($value['nombre']) . "&";
            $this->queryString .= "amount_" . $i . "=" . urlencode($value['precio']) . "&";
            $this->queryString .= "quantity_" . $i . "=" . urlencode($value['numero_productos']) . "&";
            #$this->queryString .= "tax_" . $i . "=" . urlencode($value['iva']) . "&";
            $i++;
        }

        $this->queryString .= "return=" . urlencode(stripslashes($this->url['return']));
        $this->queryString .= "&cancel_return=" . urlencode(stripslashes($this->url['cancel']));
        $this->queryString .= "&notify_url=" . urlencode($this->url['notify']);
    }

    /**
     * @return string
     */
    private function getById($id = null)
    {
        if (is_null($id)) {
            return false;
        }

        $result = CartModel::singleton()->getById($id);

        return $result;
    }

    private function getTotal($array = array())
    {
        if (!$array) {
            return false;
        }

        $total = $array['total'] * $array['tipo_cambio'];
        $total = $total - ($total * ($array['descuento'] / 100));
        $total = $total + ($total * $array['iva']);

        return $total;
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