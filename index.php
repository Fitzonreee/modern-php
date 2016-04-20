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
})->setName('contact');

$app->post('/process', function ($request, $response) {
  // var_dump($request->getParams());
  $name = $request->getParam('name');
  $email = $request->getParam('email');
  $message = $request->getParam('message');

  if (!empty($name) && !empty($email) && !empty($message)) {
    $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
    $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    $cleanMessage = filter_var($message, FILTER_SANITIZE_STRING);

  } else {
    // display error message
    return $response->withRedirect('/contact');
  }

  $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
  $mailer = \Swift_Mailer::newInstance($transport);

  $message = \Swift_Message::newInstance();
  $message->setSubject('Email about Herbert Marcuse');
  $message->setForm(array($cleanName => $cleanEmail));
  $message->setTo(array('kevin.fitzhenry@createthenext.com'));
  $message->setBody($cleanMessage);


});

// Run app
$app->run();
