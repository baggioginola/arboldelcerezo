<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 26/ene/2017
 * Time: 21:58
 */

require_once 'CBaseController.class.inc.php';

require_once __MODEL__ . 'CLoginModel.class.inc.php';

#require_once CLASSES . 'CSession.class.inc.php';

class Login extends BaseController
{
    public static $object = null;
    private $parameters = array();

    private $validParameters = array(
        'e_mail' => TYPE_ALPHA,
        'password' => TYPE_ALPHA
    );

    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function authenticate()
    {
        if (!$this->_setParameters()) {
            return json_encode($this->getResponse(STATUS_FAILURE_CLIENT, MESSAGE_ERROR));
        }

        if (!$result = LoginModel::singleton()->authenticate($this->parameters)) {
            return json_encode($this->getResponse(STATUS_FAILURE_CLIENT, MESSAGE_ERROR));
        }

        if (!$token = Encryption::singleton()->generateToken($result['nombre'], $result['apellidos'])) {
            return json_encode($this->getResponse(STATUS_FAILURE_CLIENT, MESSAGE_ERROR));
        }

        Session::singleton()->store($token);

        Session::singleton()->storeUserInfo($result['nombre'], $result['apellidos']);

        return json_encode($this->getResponse());
    }

    public function logout()
    {
        if (!Session::singleton()->destroy()) {
            return false;
        }
        return true;
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