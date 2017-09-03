<?php
/**
 * Created by PhpStorm.
 * User: mariocue
 * Date: 02/01/2017
 * Time: 10:48 AM
 */

require_once __DIR__ . '/../config.php';
require_once FRAMEWORK . 'slim/vendor/autoload.php';

if (strcasecmp(ENVIRONMENT, 'test') == 0) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$settings = array(
    'CSS' => CSS,
    'JS' => JS,
    'IMG' => IMG,
    'DOMAIN' => DOMAIN,
    'PRODUCT_IMG' => PRODUCT_IMG,
    'PROJECT_IMG' => PROJECT_IMG,
    'BANNER_IMG' => BANNER_IMG
);

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$container = $app->getContainer();

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(TWIG_TEMPLATES);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};