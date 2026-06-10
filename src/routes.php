<?php 
use App\Controllers\BookController; 
use Slim\App; 
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void { 
    $controller = new BookController(new \App\Repositories\BookRepository(\App\Database::get())); 
  
    $app->get('/', [$controller, 'healthCheck']);

    $app->group('/api', function (RouteCollectorProxy $g) use ($controller) { 
        $g->get   ('/books',       [$controller, 'index']); 
        $g->get   ('/books/{id}',  [$controller, 'show']); 
        $g->post  ('/books',       [$controller, 'create']); 
        $g->put   ('/books/{id}',  [$controller, 'update']); 
        $g->delete('/books/{id}',  [$controller, 'delete']); 
        
        // !!! CRITICAL FOR VUE/REACT FRONTEND PREFLIGHT CHECKS !!!
        $g->options('/{routes:.+}', function ($request, $response) { 
            return $response; 
        });
    }); 
};