<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 13/ene/2017
 * Time: 19:39
 */

require_once __CONTROLLER__ . 'CCategoriesController.class.inc.php';
require_once 'includes/functions.inc.php';

$categories = Categories::singleton()->getAll();

$app->get('/', function ($request, $response, $args) {
    global $settings, $categories;
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';

    $products = Products::singleton()->getRandomAll();

    $result = getProductsUrl($products);
    return $this->view->render($response, 'main.twig', array('settings' => $settings, 'categories' => $categories, 'products' => $result));
});

$app->get('/categoria/{id}', function ($request, $response, $args) {
    global $settings, $categories;
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';
    require_once __CONTROLLER__ . 'CCategoriesController.class.inc.php';

    $category = Categories::singleton()->getById($args);
    $products = Products::singleton()->getByCategory($args);

    $result = getProductsUrl($products);

    return $this->view->render($response, 'categoria.twig', array('settings' => $settings, 'categories' => $categories,
        'products' => $result, 'category' => $category));
});

$app->get('/producto/{id}', function ($request, $response, $args) {
    global $settings, $categories;
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';

    $product = Products::singleton()->getById($args);

    $result = getProductUrl($product);

    return $this->view->render($response, 'product.twig', array('settings' => $settings, 'categories' => $categories, 'producto' => $result));
});

$app->get('/quienes-somos', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'quienes-somos.twig', array('settings' => $settings));
});

$app->get('/contacto', function ($request, $response, $args) {
    global $settings;
    return $this->view->render($response, 'contacto.twig', array('settings' => $settings));
});