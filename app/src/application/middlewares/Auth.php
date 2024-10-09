<?php

namespace toubeelib\application\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Slim\Exception\HttpUnauthorizedException;
use toubeelib\application\renderer\auth\AuthProviderInterface;

class Auth
{
    private AuthProviderInterface $authProvider;
    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }
    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next)
    {
        $token = $rq->getHeader('Authorization')[0] ?? '';
        if ($token == '') {
            throw new HttpUnauthorizedException($rq, 'missing Authorization Header');
        }
        $authDTO = $this->authProvider->getSignedInUser($token);
        $rq = $rq->withAttribute('auth', $authDTO);
        return $next->handle($rq);
    }

}