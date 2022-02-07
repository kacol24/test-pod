<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Passport\Exceptions\OAuthServerException;
use Illuminate\Auth\AuthenticationException;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class CheckToken
{

    private $server;

    public function __construct(ResourceServer $server)
    {
        $this->server = $server;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $psr = (new PsrHttpFactory(
            new ServerRequestFactory,
            new StreamFactory,
            new UploadedFileFactory,
            new ResponseFactory
        ))->createRequest($request);
        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
            $token_id = $psr->getAttribute('oauth_access_token_id');
            if (!$token_id) {
                throw new AuthenticationException;    
            }
        } catch (OAuthServerException $e) {
            throw new AuthenticationException;
        }

        return $next($request);
    }
}