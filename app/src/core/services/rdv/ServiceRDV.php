<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\dto\rdv\CreateRDVDTO;
use toubeelib\core\dto\rdv\RDVDto;

class ServiceRDV implements ServiceRDVInterface
{

    public function creerRendezvous(CreateRDVDto $createRDVDTO): RDVDto
    {
        // ! Vérifie si le praticien existe
        if($createRDVDTO->praticienID == null) {
            throw new RDVPraticienNotFoundException();
        }

        $specialitePraticien = new Specialite('','',''); // ! Récupère la spécialité du praticien;
        // ! Vérifie si la spécialité du praticien correspond à la spécialité du rendez-vous demandé
        if ($createRDVDTO->specialiteDM != $specialitePraticien) {
            throw new RDVSpecialitePraticienDifferentException();
        }

        // ! Vérifie que le praticien est disponible à la date et à l'heure demandées

    }
}