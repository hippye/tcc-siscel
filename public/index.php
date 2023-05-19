<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/functions.php';

$app = AppFactory::create();

$twig = Twig::create(__DIR__ . '/../src/views', ['cache' => false]);

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->add(TwigMiddleware::create($app, $twig));

require_once __DIR__ . '/../src/routes/routes.php';

$app->run();