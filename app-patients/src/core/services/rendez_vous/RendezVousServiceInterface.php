<?php

namespace toubeelib\core\services\rendez_vous;

interface RendezVousServiceInterface
{
    public function getRendezVousByPatientId(string $id): array;

}
