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

$app->get('/carrito-compra', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;

    if ($cart_products == 0) {
        return $response->withStatus(200)->withHeader('Location', DOMAIN);
    }

    $result = Cart::singleton()->getAllProducts();

    $result = getProductsUrl($result);

    $total = number_format(getTotal($result, 'total'), 2);

    return $this->view->render($response, 'carrito-compra.twig', array('settings' => $settings, 'categories' => $categories, 'result' => $result, 'cart_products' => $cart_products['total'], 'total' => $total));
});

$app->get('/pago', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    return $this->view->render($response, 'pago.twig', array('settings' => $settings, 'categories' => $categories, 'cart_products' => $cart_products['total']));
});

$app->get('/confirmar-paypal', function ($request, $response, $args) {
    global $settings;
    require_once __CONTROLLER__ . 'CPaypalController.class.inc.php';
    $result = Paypal::singleton()->pay();

    echo $result;

    if (!$result) {
        return $response->withStatus(200)->withHeader('Location', DOMAIN);
    }

    return $response->withStatus(200)->withHeader('Location', $result);
});

$app->get('/quienes-somos', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    return $this->view->render($response, 'quienes-somos.twig', array('settings' => $settings, 'categories' => $categories, 'cart_products' => $cart_products['total']));
});

$app->get('/contacto', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;
    return $this->view->render($response, 'contacto.twig', array('settings' => $settings, 'categories' => $categories, 'cart_products' => $cart_products['total']));
});

$app->get('/buscar', function ($request, $response, $args) {
    global $settings, $categories, $cart_products;

    require_once __CONTROLLER__ . 'CSearchController.class.inc.php';

    $result = array();
    $params = $request->getQueryParams();

    $q = $params['q'];
    $result = Search::singleton()->getProductsbyQuery($q);

    $result = getProductsUrl($result);

    return $this->view->render($response, 'search.twig', array('settings' => $settings, 'categories' => $categories, 'cart_products' => $cart_products['total'], 'result' => $result));
});