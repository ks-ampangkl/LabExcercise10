<?php
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

Dotenv::createImmutable(__DIR__ . '/..')->safeLoad(); 


$app = AppFactory::create();

(require __DIR__ . '/../src/routes.php')($app);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();


$app->add(new App\Middleware\JsonBodyParser()); // ← Step 13

$app->add(new App\Middleware\Cors()); // ← Step 14

$app->addErrorMiddleware(true, true, true);


$app->run();