<?php
// use \Psr\Http\Message\ServerRequestInterface as Request;
// use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('America/Los_Angeles');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// $log = new Logger('name');
// $log->pushHandler(new StreamHandler('app.txt', Logger::WARNING));
// $log->addWarning('Oh, no!');

// Create and configure Slim app
$app = new \Slim\App([
  'settings' => ['displayErrorDetails' => true],
]);

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('templates', [
        'cache' => false
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

// Render Twig template in route
$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'about.twig');
});

$app->get('/contact', function ($request, $response) {
    return $this->view->render($response, 'contact.twig');
});

// Run app
$app->run();
