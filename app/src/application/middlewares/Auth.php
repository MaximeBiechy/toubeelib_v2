<?php

namespace toubeelib\application\middlewares;

use toubeelib\application\renderer\auth\AuthProviderInterface;

class Auth
{
    private AuthProviderInterface $authProvider;
    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }
    public function __invoke($request, $response, $next)
    {

    }

}