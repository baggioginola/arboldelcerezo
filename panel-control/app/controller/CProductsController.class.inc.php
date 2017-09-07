<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 01/feb/2017
 * Time: 20:20
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __MODEL__ . 'CProductsModel.class.inc.php';

class Products extends BaseController
{
    private static $object = null;

    private $parameters = array();

    private $log = array();

    private $validParameters = array(
        'id_producto' => TYPE_INT,
        'nombre' => TYPE_ALPHA,
        'descripcion' => TYPE_ALPHA,
        'detalles_tecnicos' => TYPE_ALPHA,
        'status' => TYPE_INT,
        'fecha_alta' => TYPE_DATE,
        'fecha_modifica' => TYPE_DATE,
        'id_categoria' => TYPE_INT,
        'iva' => TYPE_FLOAT,
        'codigo_interno' => TYPE_ALPHA,
        'moneda' => TYPE_INT,
        'id_marca' => TYPE_INT,
        'num_imagenes' => TYPE_INT,
        'precio' => TYPE_FLOAT,
        'precio_compra' => TYPE_FLOAT,
        'clave_alterna' => TYPE_ALPHA,
        'departamento' => TYPE_ALPHA
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
        if (!$result = ProductsModel::singleton()->getAll()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        return json_encode(UTF8Converter($result));
    }

    /**
     * @return string
     */
    public function getLastId()
    {
        if (!$result = ProductsModel::singleton()->getLastId()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        return json_encode($result);
    }

    /**
     * @return string
     */
    public function getById()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $result = ProductsModel::singleton()->getById($this->parameters['id_producto']);

        return json_encode(UTF8Converter($result));
    }

    /**
     * @return string
     */
    public function add()
    {
        if (!$this->_setParameters()) {
            LogsController::store();
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $this->parameters['status'] = 1;
        $this->parameters['fecha_alta'] = date('Y-m-d H:i:s');
        $this->parameters['fecha_modifica'] = date('Y-m-d H:i:s');

        $result = array();
        if (!$result['id'] = ProductsModel::singleton()->add($this->parameters)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        LogsController::store();
        return json_encode($this->getResponse(STATUS_SUCCESS, MESSAGE_SUCCESS, $result));
    }

    /**
     * @return string
     */
    public function edit()
    {
        if (!$this->_setParameters()) {
            LogsController::store();
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $id = $this->parameters['id_producto'];

        unset($this->parameters['id_producto']);

        $this->parameters['fecha_modifica'] = date('Y-m-d H:i:s');
        Debugger::add('parameters class', $this->parameters, true, __LINE__, __METHOD__);
        if (!ProductsModel::singleton()->edit($this->parameters, $id)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        LogsController::store();
        return json_encode($this->getResponse());
    }

    /**
     * @return string
     */
    public function delete()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        $id = $this->parameters['id_producto'];

        unset($this->parameters['id_producto']);

        if (!ProductsModel::singleton()->edit($this->parameters, $id)) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse());
    }

    public function addFakeData($data)
    {
        foreach ($data as $key => $value) {
            $this->parameters[$key] = $value;
        }

        $result = ProductsModel::singleton()->add($this->parameters);

        return $result;
    }


    /**
     * @return string
     */
    public function addXLS($array = array())
    {
        if (!$array) {
            return false;
        }

        $array['status'] = 1;
        $array['fecha_alta'] = date('Y-m-d H:i:s');
        $array['fecha_modifica'] = date('Y-m-d H:i:s');
        $array['iva'] = 0.16;
        $array['num_imagenes'] = 0;
        $array['likes'] = 0;

        $result = array();
        if (!$result['id'] = ProductsModel::singleton()->add($array)) {
            return false;
        }

        return $result['id'];
    }

    /**
     * @return string
     */
    public function editXLS($parameters = array())
    {
        if (!$parameters) {
            return false;
        }

        $id = $parameters['id_producto'];
        unset($parameters['id_producto']);

        $parameters['status'] = 1;
        $parameters['fecha_modifica'] = date('Y-m-d H:i:s');

        if (!ProductsModel::singleton()->edit($parameters, $id)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getByCodigoInterno($codigo_interno = null)
    {
        if (is_null($codigo_interno)) {
            return false;
        }

        $result = ProductsModel::singleton()->getByCodigoInterno($codigo_interno);

        return $result;
    }

    public function updateSales($id_product = null, $value = null)
    {
        if (is_null($id_product) || is_null($value)) {
            return false;
        }

        return ProductsModel::singleton()->updateSales($id_product, $value);
    }

    /**
     * @return bool
     */
    private function _setParameters()
    {
        if (!isset($_POST) || empty($_POST)) {
            return false;
        }

        Debugger::add('parameters', $_POST, true, __LINE__, __METHOD__);
        if (!$this->validateParameters($_POST, $this->validParameters)) {
            return false;
        }

        foreach ($_POST as $key => $value) {
            $this->parameters[$key] = $value;
        }
        return true;
    }
}