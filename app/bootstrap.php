<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

/*
 * Config
 * ---------------------------------------
 */
$config = new \Noodlehaus\Config(__DIR__ . '/config.php');

/*
 * Slim App
 * ---------------------------------------
 */
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => $config->get('debug'),
    ],
]);

$container = $app->getContainer();

/*
 * Twig
 * ---------------------------------------
 */
$container['view'] = function($c) {

    $view = new \Slim\Views\Twig(__DIR__ . '/../views');

    $view->addExtension(new \Slim\Views\TwigExtension(
        $c->router,
        $c->config->get('url')
    ));

    return $view;

};

/*
 * Routes
 * ---------------------------------------
 */
require __DIR__ . '/routes.php';