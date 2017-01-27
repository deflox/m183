<?php

session_start();

error_reporting(E_ALL & ~E_NOTICE);

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
        'db' => [
            'driver' => 'mysql',
            'host' => $config->get('db.host'),
            'database' => $config->get('db.database'),
            'username' => $config->get('db.user'),
            'password' => $config->get('db.password'),
            'charset' => $config->get('db.charset'),
            'collation' => $config->get('db.collation'),
            'port' => $config->get('db.port'),
        ],
    ],
]);

$container = $app->getContainer();

/*
 * Dependencies
 * ---------------------------------------
 */
$container['phpmailer'] = function () {
    return new PHPMailer;
};
$container['flash'] = function() {
    return new \Slim\Flash\Messages;
};
$container['config'] = function() use($config) {
    return $config;
};
$container['csrf'] = function() {
    return new \Slim\Csrf\Guard();
};

/*
 * Eloquent
 * ---------------------------------------
 */
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function() use ($capsule) {
    return $capsule;
};

/*
 * Controllers
 * ---------------------------------------
 */
$container['PageController'] = function($c) {
    return new App\Controller\PageController($c);
};
$container['AuthSecureController'] = function($c) {
    return new App\Controller\AuthSecureController($c);
};
$container['AuthInsecureController'] = function($c) {
    return new App\Controller\AuthSecureController($c);
};
$container['MovieSecureController'] = function($c) {
    return new App\Controller\MovieSecureController($c);
};

/*
 * Libraries
 * ---------------------------------------
 */
$container['mail'] = function($c) {
    return new App\Libraries\Mail($c);
};
$container['authSecure'] = function($c) {
    return new App\Libraries\AuthSecure($c);
};
$container['authInsecure'] = function($c) {
    return new App\Libraries\AuthInsecure($c);
};
$container['validation'] = function($c) {
    return new App\Libraries\Validation($c);
};
$container['movies'] = function($c) {
    return new App\Libraries\Movies($c);
};
$container['api'] = function($c) {
    return new App\Libraries\API($c);
};

/*
 * Middleware
 * ---------------------------------------
 */
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\ValidationErrorMiddleware($container));
$app->add(new App\Middleware\ViewMiddleware($container));

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

    $view->getEnvironment()->addGlobal('flash', $c->flash);

    return $view;

};

/*
 * Routes
 * ---------------------------------------
 */
require __DIR__ . '/routes.php';