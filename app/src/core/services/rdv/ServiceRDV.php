<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\dto\rdv\CreateRDVDTO;
use toubeelib\core\dto\rdv\RDVDto;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;

class ServiceRDV implements ServiceRDVInterface
{
    private ArrayPraticienRepository $praticienRepository;
    private ArrayRDVRepository $rdvRepository;

    public function __construct(ArrayPraticienRepository $praticienRepository, ArrayRDVRepository $rdvRepository)
    {
        $this->praticienRepository = $praticienRepository;
        $this->rdvRepository = $rdvRepository;
    }

    public function creerRendezvous(CreateRDVDto $createRDVDTO): RDVDto
    {
        // ! Vérifie si le praticien existe
        if($createRDVDTO->praticienID == null) {
            throw new RDVPraticienNotFoundException();
        }

        $specialitePraticien = $this->praticienRepository->getPraticienById($createRDVDTO->praticien_id); // ! Récupère la spécialité du praticien;
        // ! Vérifie si la spécialité du praticien correspond à la spécialité du rendez-vous demandé
        if ($createRDVDTO->specialiteDM != $specialitePraticien) {
            throw new RDVSpecialitePraticienDifferentException();
        }

        // ! Vérifie que le praticien est disponible à la date et à l'heure demandées

    }
}