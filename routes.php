<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 13/ene/2017
 * Time: 19:39
 */

require_once __CONTROLLER__ . 'CCategoriesController.class.inc.php';
require_once __CONTROLLER__ . 'CCartController.class.inc.php';
require_once 'includes/functions.inc.php';

$cart_products = Cart::singleton()->getAll();

$categories = Categories::singleton()->getAll();

$app->get('/', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';

    $products = Products::singleton()->getRandomAll();

    $result = getProductsUrl($products);
    return $this->view->render($response, 'main.twig', array('settings' => $settings, 'categories' => $categories, 'products' => $result, 'cart_products' => $cart_products['total']));
});

$app->get('/categoria/{id}', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';
    require_once __CONTROLLER__ . 'CCategoriesController.class.inc.php';

    $category = Categories::singleton()->getById($args);
    $products = Products::singleton()->getByCategory($args);

    $result = getProductsUrl($products);

    return $this->view->render($response, 'categoria.twig', array('settings' => $settings, 'categories' => $categories,
        'products' => $result, 'category' => $category, 'cart_products' => $cart_products['total']));
});

$app->get('/producto/{id}', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    require_once __CONTROLLER__ . 'CProductsController.class.inc.php';

    $product = Products::singleton()->getById($args);

    $result = getProductUrl($product);

    return $this->view->render($response, 'product.twig', array('settings' => $settings, 'categories' => $categories, 'producto' => $result, 'cart_products' => $cart_products['total']));
});

$app->get('/quienes-somos', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    return $this->view->render($response, 'quienes-somos.twig', array('settings' => $settings, 'categories' => $categories, 'cart_products' => $cart_products['total']));
});

$app->get('/contacto', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    return $this->view->render($response, 'contacto.twig', array('settings' => $settings, 'categories' => $categories, 'cart_products' => $cart_products['total']));
});