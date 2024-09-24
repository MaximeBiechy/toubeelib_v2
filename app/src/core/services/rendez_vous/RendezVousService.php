<?php

namespace toubeelib\core\services\rendez_vous;

use Psr\Log\LoggerInterface;
use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\dto\rendez_vous\CancelRendezVousDTO;
use toubeelib\core\dto\rendez_vous\CreateRendezVousDTO;
use toubeelib\core\dto\rendez_vous\DisponibilityPraticienRendezVousDTO;
use toubeelib\core\dto\rendez_vous\RendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdatePatientRendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdateSpecialityRendezVousDTO;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class RendezVousService implements RendezVousServiceInterface
{
    private PraticienRepositoryInterface $praticienRepository;
    private RendezVousRepositoryInterface $rdvRepository;

    private PatientRepositoryInterface $patientRepository;
    private LoggerInterface $logger;

    public function __construct(PraticienRepositoryInterface $praticienRepository, RendezVousRepositoryInterface $rdvRepository, PatientRepositoryInterface $patientRepository,LoggerInterface $logger)
    {
        $this->praticienRepository = $praticienRepository;
        $this->rdvRepository = $rdvRepository;
        $this->patientRepository = $patientRepository;
        $this->logger = $logger;
    }

    public function creerRendezvous(CreateRendezVousDTO $createRDVDTO): RendezVousDTO
    {
        // ! Vérifie si le praticien existe
        if ($createRDVDTO->praticienID == null) {
            throw new RendezVousPraticienNotFoundException();
        }
        $praticien = $this->praticienRepository->getPraticienById($createRDVDTO->praticienID); // ! Récupère le praticien
        $specialitePraticien = $praticien->getSpecialite(); // ! Récupère la spécialité du praticien;
        // ! Vérifie si la spécialité du praticien correspond à la spécialité du rendez-vous demandé
        if ($createRDVDTO->specialiteDM != $specialitePraticien) {
            throw new RendezVousSpecialitePraticienDifferentException();
        }

        // ! Vérifie que le praticien est disponible à la date et à l'heure demandées
        $rdvs = $this->rdvRepository->getRDVByPraticienId($createRDVDTO->praticienID);
        if ($rdvs != null) {
            foreach ($rdvs as $rdv) {
                if ($rdv->getDate() == $createRDVDTO->date) {
                    throw new RendezVousPraticienNotAvailableException();
                }
            }
        }

        $rendezVous = new RendezVous($createRDVDTO->praticienID, $createRDVDTO->patientID, $specialitePraticien, $createRDVDTO->date); // ! Crée le rendez-vous

        $this->rdvRepository->saveRDV($rendezVous); // ! Enregistre le rendez-vous

        return new RendezVousDTO($rendezVous);
    }

    public function consultingRendezVous(string $id): RendezVousDTO
    {
        try {
            $rdv = $this->rdvRepository->getRDVById($id);
            return new RendezVousDTO($rdv);

        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousNotFoundException('rdv not found');
        }

    }


    public function annulerRendezvous(CancelRendezVousDTO $cancelRendezVousDTO): void
    {
        try {
            $rdv = $this->rdvRepository->getRDVById($cancelRendezVousDTO->id);
            $rdv->annuler();
            $this->rdvRepository->saveRDV($rdv);
            $this->logger->info('RDV cancelled', ['id' => $cancelRendezVousDTO->id]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousNotFoundException();
        }
    }

    public function updateSpecialityRendezVous(UpdateSpecialityRendezVousDTO $dto): void
    {
        try{
            $rdv = $this->rdvRepository->getRDVById($dto->id);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RendezVousNotFoundException();
        }
        try {

            $speciality = $this->praticienRepository->getSpecialiteById($dto->speciality);
            $rdv->setSpeciality($dto->speciality);
            $this->rdvRepository->saveRDV($rdv);
            $this->logger->info('RDV speciality updated', ['id' => $dto->id, 'speciality' => $dto->speciality]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        }

    }

    public function updatePatientRendezVous(UpdatePatientRendezVousDTO $dto): void
    {
        try{
            $rdv = $this->rdvRepository->getRDVById($dto->id);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RendezVousNotFoundException();
        }
        try {
            $this->patientRepository->getPatientById($dto->patientID);
            $rdv->setPatientID($dto->patientID);
            $this->rdvRepository->saveRDV($rdv);
            $this->logger->info('RDV patient updated', ['id' => $dto->id, 'patientID' => $dto->patientID]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        }
    }

    public function updateRendezVous(string $id, CreateRendezVousDTO $createRDVDto): RendezVousDTO
    {
        try {
            $this->annulerRendezvous($id);
            $this->logger->info('RDV cancelled', ['id' => $id]);
            $this->logger->info('RDV add', ['id' => $id]);
            return $this->creerRendezvous($createRDVDto);
        } catch (RendezVousNotFoundException $e) {
            throw new RendezVousNotFoundException();
        }
    }

    public function getDisponibilityPraticienRendezVous(DisponibilityPraticienRendezVousDTO $disponibilityPraticienRDVDto): array
    {
        $rdvs = $this->rdvRepository->getRDVByPraticienId($disponibilityPraticienRDVDto->idPraticien);
        $disponibility = [];
        if ($disponibilityPraticienRDVDto->dateDebut > $disponibilityPraticienRDVDto->dateFin) {
            throw new RendezVousBadDataException();
        }
        $date = $disponibilityPraticienRDVDto->dateDebut;
        while ($date < $disponibilityPraticienRDVDto->dateFin) {
            $isDispo = true;
            foreach ($rdvs as $rdv) {
                if ($rdv->getDate() == $date) {
                    $isDispo = false;
                    break;
                }
            }
            if ($isDispo && $date->format('H') >= 9 && $date->format('H') < 18) {
                $disponibility[] = $date;
            }
            $date = $date->add(new \DateInterval('PT' . $disponibilityPraticienRDVDto->duree . 'M'));
        }
        return $disponibility;
    }

}