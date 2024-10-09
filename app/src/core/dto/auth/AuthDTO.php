<?php

namespace toubeelib\core\dto\auth;

use toubeelib\core\dto\DTO;

class AuthDTO extends DTO
{
    private string $id;
    private string $email;
    private int $role;
    private ?string $token;
    private ?string $token_refresh;

    public function __construct(string $id, string $email, int $role, string $token = null, string $token_refresh = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
        $this->token = $token;
        $this->token_refresh = $token_refresh;
    }

}