<?php

namespace toubeelib\application\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use toubeelib\core\services\rendez_vous\AuthorizationRendezVousServiceInterface;

class AuthzRendezVous
{
    private AuthorizationRendezVousServiceInterface $authzRendezVousService;

    public function __construct(AuthorizationRendezVousServiceInterface $authzRendezVousService)
    {
        $this->authzRendezVousService = $authzRendezVousService;
    }

    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next)
    {
        $user = $rq->getAttribute('auth');
        $routeContext = RouteContext::fromRequest($rq);
        $route = $routeContext->getRoute();
        $rendezVousId = $route->getArguments()['ID-RDV'];
        if($this->authzRendezVousService->isGranted($user->id,1, $rendezVousId, 0 ))
            return $next->handle($rq);
        else if ($this->authzRendezVousService->isGranted($user->id,1, $rendezVousId, 10 ))
            return $next->handle($rq);
        else
            throw new HttpUnauthorizedException($rq, 'Unauthorized');
    }

}