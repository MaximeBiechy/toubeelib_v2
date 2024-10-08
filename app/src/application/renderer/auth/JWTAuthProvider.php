<?php

namespace toubeelib\application\renderer\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\services\auth\AuthentificationServiceInterface;

class JWTAuthProvider implements AuthProviderInterface
{

    private string $secretKey = 'your_secret_key';
    private AuthentificationServiceInterface $authService;

    public function __construct(AuthentificationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(string $email, string $password): void
    {
        // Hash the password and store the user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->authService->register(new CredentialsDTO($email, $hashedPassword));
    }

    public function signin(string $email, string $password): void
    {
        // Check the user credentials
        $token = $this->authService->byCredentials(new CredentialsDTO($email, $password));

    }

    public function signout(): void
    {
        // Invalidate the token (this can be done by maintaining a blacklist of tokens)
    }

    public function isSignedIn(string $email): bool
    {
        // Check if the user is signed in (this can be done by checking the token validity)
    }

    public function getSignedInUser(): array
    {

    }
}