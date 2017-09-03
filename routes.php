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

$app->get('/categoria/{id_categoria}', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'categoria.twig', array('settings' => $settings));
});

$app->get('/producto/{id_producto}', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'product.twig', array('settings' => $settings));
});

$app->get('/quienes-somos', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'quienes-somos.twig', array('settings' => $settings));
});

$app->get('/contacto', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'contacto.twig', array('settings' => $settings));
});