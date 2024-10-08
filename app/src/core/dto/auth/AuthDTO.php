<?php

namespace toubeelib\core\dto\auth;

use toubeelib\core\dto\DTO;

class AuthDTO extends DTO
{
    private string $id;
    private string $email;
    private string $password;
    private int $role;
    private ?string $token;

    public function __construct(string $id, string $email, string $password, int $role, string $token = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->token = $token;
    }

}