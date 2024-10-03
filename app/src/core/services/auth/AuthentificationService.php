<?php

namespace toubeelib\core\services\auth;

use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\dto\auth\TokenDTO;

class AuthentificationService implements AuthentificationServiceInterface
{

    public function register(CredentialsDTO $credentials): string
    {
        // TODO: Implement register() method.
    }

    public function byCredentials(CredentialsDTO $credentials): TokenDTO
    {
        // TODO: Implement byCredentials() method.
    }

    public function byToken(TokenDTO $refreshToken): TokenDTO
    {
        // TODO: Implement byToken() method.
    }
}