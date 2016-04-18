<?php
require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('America/Los_Angeles');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('name');
$log->pushHandler(new StreamHandler('app.txt', Logger::WARNING));

$log->addWarning('Oh, no!');


echo "Hello, Kevin!";
