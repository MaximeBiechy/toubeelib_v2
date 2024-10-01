<?php

namespace toubeelib\infrastructure\db;

use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;

class PDORendezVousRepository implements RendezVousRepositoryInterface
{

    public function saveRDV(RendezVous $rdv): string
    {
        // TODO: Implement saveRDV() method.
    }

    public function getRDVById(string $id): RendezVous
    {
        // TODO: Implement getRDVById() method.
    }

    public function getRDVByPraticienId(string $praticienId): array
    {
        // TODO: Implement getRDVByPraticienId() method.
    }
}