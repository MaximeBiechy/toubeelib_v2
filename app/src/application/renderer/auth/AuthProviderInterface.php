<?php

namespace toubeelib\application\renderer\auth;

interface AuthProviderInterface
{
    public function register(string $email, string $password): void;
    public function signin(string $email, string $password): void;
    public function signout(): void;
    public function isSignedIn(string $email): bool;
    public function getSignedInUser(): array;

}