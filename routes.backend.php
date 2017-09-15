<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 15/ene/2017
 * Time: 18:47
 */

$app->group('/cart', function () use ($app) {
    $app->post('/add', function () use ($app) {
        require_once __CONTROLLER__ . 'CCartController.class.inc.php';
        $result = Cart::singleton()->add();
        echo $result;
    });
    $app->post('/delete', function () use ($app) {
        require_once __CONTROLLER__ . 'CCartController.class.inc.php';
        $result = Cart::singleton()->delete();
        echo $result;
    });
});