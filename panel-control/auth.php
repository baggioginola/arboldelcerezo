<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 26/ene/2017
 * Time: 21:58
 */

require_once CLASSES . 'CSession.class.inc.php';

class CAuth
{
    public function authenticate()
    {
        return Session::singleton()->validate();
    }

    public function __invoke($request, $response, $next)
    {
        if (!$this->authenticate()) {
            return $response->withStatus(200)->withHeader('Location', DOMAIN . 'login');
        }

        $response = $next($request, $response);

        return $response;

    }
}