<?php

namespace toubeelib\application\provider\auth;

use toubeelib\core\dto\auth\AuthDTO;

interface AuthProviderInterface
{
    public function register(string $email, string $password): void;
    public function signin(string $email, string $password): AuthDTO;
    public function refresh(string $token): AuthDTO;
    public function getSignedInUser(string $token): AuthDTO;

}