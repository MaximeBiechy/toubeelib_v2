<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rendez_vous\RendezVous;

interface RendezVousRepositoryInterface
{
    public function save(RendezVous $rdv): string;
    public function getRendezVousById(string $id): RendezVous;
    public function getRendezVousByPraticienId(string $praticienId): array;
    public function getRendezVousByPatientId(string $id);

}