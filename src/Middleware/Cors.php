<?php // src/Middleware/Cors.php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Cors implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 1. Instantly return a clean 204 response for browser Preflight OPTIONS checks
        if ($request->getMethod() === 'OPTIONS') {
            $response = new \Slim\Psr7\Response(204);
            return $this->withCors($response);
        }

        // 2. Process the request down the line
        $response = $handler->handle($request);
        
        // 3. Stamp the CORS headers on the resulting response on its way out
        return $this->withCors($response);
    }
 
    private function withCors(ResponseInterface $r): ResponseInterface {
        return $r
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}