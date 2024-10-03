<?php

namespace toubeelib\core\dto\auth;

use toubeelib\core\dto\DTO;

class TokenDTO extends DTO
{
    protected string $token;
    protected string $refreshToken;

    public function __construct(string $token, string $refreshToken)
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
    }

}