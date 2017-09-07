<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 30/ene/2017
 * Time: 21:41
 */
require_once CLASSES . 'CSession.class.inc.php';

$name = Session::singleton()->getName();
$last_name = Session::singleton()->getLastName();

$settings['NAME'] = $name;
$settings['LAST_NAME'] = $last_name;

$app->get('/', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'main.twig', array('settings' => $settings));
})->add(new CAuth());

$app->get('/usuarios', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'users.twig', array('settings' => $settings));
})->add(new CAuth());

$app->get('/categorias', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'categories.twig', array('settings' => $settings));
})->add(new CAuth());

$app->get('/productos', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'products.twig', array('settings' => $settings));
})->add(new CAuth());

$app->get('/login', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'login.twig', array('settings' => $settings));
});

$app->get('/logout', function ($request, $response, $args) {
    require_once __CONTROLLER__ . 'CLoginController.class.inc.php';

    if (Login::singleton()->logout()) {
        return $response->withStatus(200)->withHeader('Location', DOMAIN);
    }
});