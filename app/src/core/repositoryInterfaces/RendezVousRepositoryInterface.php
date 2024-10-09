<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rendez_vous\RendezVous;

interface RendezVousRepositoryInterface
{
    public function saveRDV(RendezVous $rdv): string;
    public function getRDVById(string $id): RendezVous;
    public function getRDVByPraticienId(string $praticienId): array;
    public function getRendezVousByPatientId(string $id);

}