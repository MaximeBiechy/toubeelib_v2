<?php

namespace toubeelib\core\services\rendez_vous;

interface AuthorizationRendezVousServiceInterface
{
    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool;

}