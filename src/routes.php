<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\Controllers\BookController;

return function (App $app): void {
    
    // Task 13: Refactored clean root health check route
    $app->get('/', [BookController::class, 'healthCheck']);

    // The entire API group MUST live inside this wrapper function
    $app->group('/api', function ($g) {
        $g->get('/books', [BookController::class, 'index']);
        $g->get('/books/{id}', [BookController::class, 'show']);
        $g->post('/books', [BookController::class, 'create']);
        $g->put('/books/{id}', [BookController::class, 'update']);
        $g->delete('/books/{id}', [BookController::class, 'delete']);
        $g->post('/reset', [BookController::class, 'reset']); 

    $g->options('/{routes:.+}', function ($request, $response) {
        return $response; // Just return an empty 200 response; the middleware handles the headers!
    });        
    });


}; // This brace safely closes the return function at the very end