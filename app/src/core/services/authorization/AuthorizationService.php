<?php

namespace toubeelib\core\services\authorization;

class AuthorizationService implements AuthorizationServiceInterface
{

    public function isGranted(string $user_id, int $operation, string $ressource_id): bool
    {
        // TODO: Implement isGranted() method.
    }

    private function isAdmin(string $user_id): bool
    {
        // TODO: Implement isAdmin() method.
    }

    private function isOwner(string $user_id, string $ressource_id): bool
    {
        // TODO: Implement isOwner() method.
    }
}