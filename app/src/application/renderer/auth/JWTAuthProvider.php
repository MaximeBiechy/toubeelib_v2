<?php

namespace toubeelib\application\renderer\auth;

class JWTAuthProvider implements AuthProviderInterface
{

    public function register(string $email, string $password): void
    {
        // TODO: Implement register() method.
    }

    public function signin(string $email, string $password): void
    {
        // TODO: Implement signin() method.
    }

    public function signout(): void
    {
        // TODO: Implement signout() method.
    }

    public function isSignedIn(string $email): bool
    {
        // TODO: Implement isSignedIn() method.
    }

    public function getSignedInUser(): array
    {
        // TODO: Implement getSignedInUser() method.
    }
}