<?php

namespace toubeelib\application\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use toubeelib\core\services\patient\AuthorizationPatientServiceInterface;

class AuthzPatient
{

    private AuthorizationPatientServiceInterface $authzPatientService;

    public function __construct(AuthorizationPatientServiceInterface $authzPatientService)
    {
        $this->authzPatientService = $authzPatientService;
    }

    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next)
    {
        $user = $rq->getAttribute('auth');

        $routeContext = RouteContext::fromRequest($rq) ;
        $route = $routeContext->getRoute();
        $patientId = $route->getArguments()['ID-PATIENT'] ;
        if($this->authzPatientService->isGranted($user->id,1,$patientId, 0 ))
            return $next->handle($rq);
        else
            throw new HttpForbiddenException($rq, 'Forbidden');
    }

}