<?php // src/routes.php

use App\Controller\BookController; // Note: Ensure this matches your namespace (App\Controllers or App\Controller)
use App\Repositories\BookRepository;
use App\Database;
use Slim\Routing\RouteCollectorProxy;

return function ($app) {
    
    // Health Check Route
    $app->get('/', function ($request, $response) {
        $db = Database::connect(); // Quick sanity check
        $controller = new BookController(new BookRepository($db));
        return $controller->healthCheck($request, $response);
    });

    // Your /api group wrapper
    $app->group('/api', function (RouteCollectorProxy $g) {
        
        // Factory pattern: Build the dependencies on every request
        $db = Database::connect();
        $repo = new BookRepository($db);
        $controller = new BookController($repo);

        // Bind routes directly to your updated controller object instance
        $g->get('/books', [$controller, 'index']);
        $g->get('/books/{id}', [$controller, 'show']);
        $g->post('/books', [$controller, 'create']);
        $g->put('/books/{id}', [$controller, 'update']);
        $g->delete('/books/{id}', [$controller, 'delete']);
        $g->post('/reset', [$controller, 'reset']); 
        
        $g->options('/{routes:.+}', function ($request, $response) {
            return $response;
        });
    });
};