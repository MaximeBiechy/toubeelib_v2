<?php

namespace toubeelib\application\renderer\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Hoa\Compiler\Llk\Rule\Token;
use toubeelib\core\dto\auth\AuthDTO;
use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\services\auth\AuthentificationServiceInterface;

class JWTAuthProvider implements AuthProviderInterface
{

    private AuthentificationServiceInterface $authService;

    public function __construct(AuthentificationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(string $email, string $password): void
    {
        $credentials = new CredentialsDTO($email, $password);
        $this->authService->register($credentials, 0);
    }

    public function signin(string $email, string $password): AuthDTO
    {
        $credentials = new CredentialsDTO($email, $password);
        $authDTO = $this->authService->byCredentials($credentials);
        $payload = [
            'aud' => 'toubeelib',
            "iat" => time(),
            "exp" => time() + 3600,
            "sub" => $authDTO->id,
            "data" => [
                "email" => $authDTO->email,
                "role" => $authDTO->role
            ]
        ];
        $jwt = JWT::encode($payload, getenv('JWT_SECRET_KEY'), 'HS512');
        return new AuthDTO($authDTO->id, $authDTO->email, $authDTO->password, $authDTO->role, $jwt);
    }

    public function refresh(string $token): AuthDTO
    {
        $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET_KEY'), 'HS512'));
        $payload = [
            'aud' => 'toubeelib',
            "iat" => time(),
            "exp" => time() + 3600,
            "sub" => $decoded->sub,
            "data" => [
                "email" => $decoded->data->email,
                "role" => $decoded->data->role
            ]
        ];
        $jwt = JWT::encode($payload, getenv('JWT_SECRET_KEY'), 'HS512');
        return new AuthDTO($decoded->sub, $decoded->data->email, getenv('JWT_SECRET_KEY'), $decoded->data->role, $jwt);
    }

    public function getSignedInUser(string $token): AuthDTO
    {
        $decoded = JWT::decode($token, new Key(getenv('JWT_SECRET_KEY'), 'HS512'));
        return new AuthDTO($decoded->sub, $decoded->data->email, getenv('JWT_SECRET_KEY'), $decoded->data->role, $token);
    }
}