<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\rdv\CreateRDVDTO;
use toubeelib\core\dto\rdv\RDVDto;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;

class ServiceRDV implements ServiceRDVInterface
{
    private PraticienRepositoryInterface $praticienRepository;
    private RdvRepositoryInterface $rdvRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository, RdvRepositoryInterface $rdvRepository)
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

        $specialitePraticien = $this->praticienRepository->getPraticienById($createRDVDTO->praticien_id)->getSpecialite(); // ! Récupère la spécialité du praticien;
        // ! Vérifie si la spécialité du praticien correspond à la spécialité du rendez-vous demandé
        if ($createRDVDTO->specialiteDM != $specialitePraticien) {
            throw new RDVSpecialitePraticienDifferentException();
        }

        // ! Vérifie que le praticien est disponible à la date et à l'heure demandées
        $rdvs = $this->getRDVByPraticienId($createRDVDTO->praticienID);
        if ($rdvs != null) {
            foreach ($rdvs as $rdv) {
                if ($rdv->getDate() == $createRDVDTO->date) {
                    throw new RDVPraticienNotAvailableException();
                }
            }
        }

        $rendezVous = new RendezVous($createRDVDTO->praticienID, $createRDVDTO->patientID, $specialitePraticien, $createRDVDTO->date); // ! Crée le rendez-vous

        $this->rdvRepository->saveRDV($rendezVous); // ! Enregistre le rendez-vous

        return new RDVDto($rendezVous);
    }

    public function getRDVByPraticienId(string $praticienID): array
    {
        return [];
    }
}