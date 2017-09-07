<?php
/**
 * Created by PhpStorm.
 * User: mariocue
 * Date: 02/01/2017
 * Time: 10:48 AM
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.inc.php';
require_once FRAMEWORK . 'slim/vendor/autoload.php';
require_once __DIR__ . '/../auth.php';

require_once CLASSES . 'CDebugger.class.inc.php';
require_once CLASSES . 'CErrorHandler.class.inc.php';
require_once __CONTROLLER__ . 'CLogsController.class.inc.php';

if (strcasecmp(ENVIRONMENT, 'test') == 0) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set("gd.jpeg_ignore_warning", 1);
}

session_start();
$settings = array(
    'CSS' => CSS,
    'JS' => JS,
    'IMG' => IMG,
    'DOMAIN' => DOMAIN,
    'PRODUCT_IMG' => PRODUCT_IMG,
    'DATATABLE' =>DATATABLE,
    'FILEINPUT' => FILEINPUT
);

$app = new \Slim\App;

$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(TWIG_TEMPLATES);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};