<?php
/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 7/18/2016
 * Time: 5:02 PM
 */

require_once 'CBaseController.class.inc.php';
require_once __MODEL__ . 'CProductsModel.class.inc.php';

/**
 * Class Products
 */
class Products extends BaseController
{
    private static $object = null;

    private $parameters = array();

    private $validParameters = array('id' => TYPE_INT);

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
     * @return array|bool
     */
    public function getAll()
    {
        if ($result = ProductsModel::singleton()->getAll()) {
            return $result;
        }
        return false;
    }

    /**
     * @return array|bool
     */
    public function getRandomAll()
    {
        if ($result = ProductsModel::singleton()->getRandomAll()) {
            return $result;
        }
        return false;
    }

    /**
     * @param $parameters
     * @return array|bool|null
     */
    public function getById($parameters)
    {
        if (!$this->_setParameters($parameters)) {
            return false;
        }

        if ($result = ProductsModel::singleton()->getById($this->parameters['id'])) {
            return $result;
        }
        return false;
    }

    /**
     * @param $parameters
     * @return array|bool
     */
    public function getByCategory($parameters)
    {
        if (!$this->_setParameters($parameters)) {
            return false;
        }

        if (!$result = ProductsModel::singleton()->getByCategory($this->parameters['id'])) {
            return false;
        }

        return $result;
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