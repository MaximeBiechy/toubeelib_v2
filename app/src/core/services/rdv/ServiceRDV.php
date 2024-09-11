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
        $praticien = $this->praticienRepository->getPraticienById($createRDVDTO->praticienID); // ! Récupère le praticien
        $specialitePraticien = $praticien->getSpecialite(); // ! Récupère la spécialité du praticien;
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

        return new RDVDto($rendezVous, $praticien);
    }

    public function consultingRDV(string $id):RDVDto{
        try{
            //On réucpère le rdv via son id :
            $rdv = $this->rdvRepository->getRDVById($id);
            
            //On récupère la date :
            $rdv_date = $rdv->$date;


            //Informations à récupérer :
            // $this->id = null;
            // $this->date = $date;
            // $this->praticienID = $praticienID;
            // $this->patientID = $patientID;
            // $this->speciality = $speciality;
            // $this->statut = self::STATUT_PREVU;

            // $rdvDTO = $rdv->toDTO();


            return new RDVDto($rdv);    

        }
        catch{
            throw new RDVNotFoundException('Rendez-vous non-trouvé.');
        }
    }

    public function getRDVByPraticienId(string $praticienID): array
    {
        return [];
    }

    public function annulerRendezvous(string $id): void
    {
        $rdv = $this->rdvRepository->getRDVById($id);
        $rdv->annuler();
        $this->rdvRepository->saveRDV($rdv);

    }
}