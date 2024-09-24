<?php

namespace toubeelib\core\services\rendez_vous;

use toubeelib\core\dto\rendez_vous\CancelRendezVousDTO;
use toubeelib\core\dto\rendez_vous\CreateRendezVousDTO;
use toubeelib\core\dto\rendez_vous\DisponibilityPraticienRendezVousDTO;
use toubeelib\core\dto\rendez_vous\RendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdatePatientRendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdateSpecialityRendezVousDTO;

interface RendezVousServiceInterface
{
    public function creerRendezvous(CreateRendezVousDTO $createRDVDTO): RendezVousDTO;

    public function consultingRendezVous(string $id): RendezVousDTO;

    public function annulerRendezvous(string $id): void;

    public function getDisponibilityPraticienRendezVous(DisponibilityPraticienRendezVousDTO $disponibilityPraticienRDVDto): array;

    public function updateRendezVous(string $id, CreateRendezVousDTO $createRDVDto): RendezVousDTO;

    public function updateSpecialityRendezVous(UpdateSpecialityRendezVousDTO $dto): void;

    public function updatePatientRendezVous(UpdatePatientRendezVousDTO $dto): void;
}