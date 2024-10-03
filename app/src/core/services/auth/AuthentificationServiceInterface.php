<?php

namespace toubeelib\core\services\auth;

use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\dto\auth\TokenDTO;

interface AuthentificationServiceInterface
{
    public function register(CredentialsDTO $credentials): string;
    public function byCredentials(CredentialsDTO $credentials): TokenDTO;
    public function byToken(TokenDTO $refreshToken): TokenDTO;

}