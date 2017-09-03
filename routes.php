<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 13/ene/2017
 * Time: 19:39
 */

$app->get('/', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'main.twig', array('settings' => $settings));
});

$app->get('/producto/{id_producto}', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'product.twig', array('settings' => $settings));
});