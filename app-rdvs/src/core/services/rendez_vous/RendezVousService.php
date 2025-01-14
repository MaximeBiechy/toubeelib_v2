<?php

namespace toubeelib\core\services\rendez_vous;

use Psr\Log\LoggerInterface;
use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\dto\rendez_vous\CalendarRendezVousDTO;
use toubeelib\core\dto\rendez_vous\CreateRendezVousDTO;
use toubeelib\core\dto\rendez_vous\DisponibilityPraticienRendezVousDTO;
use toubeelib\core\dto\rendez_vous\RendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdatePatientRendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdateSpecialityRendezVousDTO;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;

class RendezVousService implements RendezVousServiceInterface
{
    private ServicePraticienInterface $praticienService;
    private RendezVousRepositoryInterface $rdvRepository;

    private PatientServiceInterface $patientService;
    private LoggerInterface $logger;

    public function __construct(ServicePraticienInterface $praticienRepository, RendezVousRepositoryInterface $rdvRepository, PatientServiceInterface $patientRepository,LoggerInterface $logger)
    {
        $this->praticienService = $praticienRepository;
        $this->rdvRepository = $rdvRepository;
        $this->patientService = $patientRepository;
        $this->logger = $logger;
    }

    public function creerRendezvous(CreateRendezVousDTO $createRDVDTO): RendezVousDTO
    {
        try{
            if ($createRDVDTO->praticienID == null) {
                throw new RendezVousPraticienNotFoundException();
            }
            $praticien = $this->praticienService->getPraticienById($createRDVDTO->praticienID);
            $specialitePraticien = $praticien->specialite_id;

            if ($createRDVDTO->specialiteDM != $specialitePraticien) {
                throw new RendezVousSpecialitePraticienDifferentException($createRDVDTO->specialiteDM . '!=' . $specialitePraticien);
            }

            // ! Vérifie que le praticien est disponible à la date et à l'heure demandées
            $rdvs = $this->rdvRepository->getRendezVousByPraticienId($createRDVDTO->praticienID);
            if ($rdvs != null) {
                foreach ($rdvs as $rdv) {
                    if ($rdv->getDate()->format('Y-m-d H:i:s') == $createRDVDTO->date) {
                        throw new RendezVousPraticienNotAvailableException();
                    }
                }
            }

            $rendezVous = new RendezVous($createRDVDTO->praticienID, $createRDVDTO->patientID, $specialitePraticien, $createRDVDTO->date); // ! Crée le rendez-vous

            $this->rdvRepository->save($rendezVous); // ! Enregistre le rendez-vous

            return new RendezVousDTO($rendezVous);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError( $e->getMessage());
        }
    }

    public function consultingRendezVous(string $id): RendezVousDTO
    {
        try {
            $rdv = $this->rdvRepository->getRendezVousById($id);
            return new RendezVousDTO($rdv);

        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousNotFoundException('rdv not found');
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }

    }


    public function annulerRendezvous(string $id): void
    {
        try {
            $rdv = $this->rdvRepository->getRendezVousById($id);
            $rdv->annuler();
            $this->rdvRepository->save($rdv);
            $this->logger->info('RDV cancelled', ['id' => $id]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousNotFoundException();
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }
    }

    public function updateSpecialityRendezVous(UpdateSpecialityRendezVousDTO $dto): void
    {
        try{
            $rdv = $this->rdvRepository->getRendezVousById($dto->id);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RendezVousNotFoundException();
        }
        try {

            $speciality = $this->praticienService->getSpecialiteById($dto->speciality);
            $rdv->setSpeciality($dto->speciality);
            $this->rdvRepository->save($rdv);
            $this->logger->info('RDV speciality updated', ['id' => $dto->id, 'speciality' => $dto->speciality]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }

    }

    public function updatePatientRendezVous(UpdatePatientRendezVousDTO $dto): void
    {
        try{
            $rdv = $this->rdvRepository->getRendezVousById($dto->id);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RendezVousNotFoundException();
        }
        try {
            $this->patientService->getPatientById($dto->patientID);
            $rdv->setPatientID($dto->patientID);
            $this->rdvRepository->save($rdv);
            $this->logger->info('RDV patient updated', ['id' => $dto->id, 'patientID' => $dto->patientID]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
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
        } catch (RendezVousInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }
    }

    public function getDisponibilityPraticienRendezVous(DisponibilityPraticienRendezVousDTO $disponibilityPraticienRDVDto): array
    {
        try{
            $rdvs = $this->rdvRepository->getRendezVousByPraticienId($disponibilityPraticienRDVDto->idPraticien);
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
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }

    }

    public function honorRendezVous(string $id): void
    {
        try {
            $rdv = $this->rdvRepository->getRendezVousById($id);
            $rdv->realiser();
            $this->rdvRepository->save($rdv);
            $this->logger->info('RDV honored', ['id' => $id]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousNotFoundException();
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        } catch (\Exception $e) {
            throw new RendezVousBadDataException($e->getMessage());
        }
    }

    public function nonHonorRendezVous(string $id): void
    {
        try {
            $rdv = $this->rdvRepository->getRendezVousById($id);
            $rdv->nonHonore();
            $this->rdvRepository->save($rdv);
            $this->logger->info('RDV non honored', ['id' => $id]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousNotFoundException();
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }
    }

    public function payRendezVous(string $id): void
    {
        try {
            $rdv = $this->rdvRepository->getRendezVousById($id);
            $rdv->payer();
            $this->rdvRepository->save($rdv);
            $this->logger->info('RDV payed', ['id' => $id]);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousNotFoundException();
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        } catch (\Exception $e) {
            throw new RendezVousBadDataException($e->getMessage());
        }
    }

    public function getRendezVousByPraticien(string $id): array
    {
        try {
            return $this->rdvRepository->getRendezVousByPraticienId($id);
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }
    }

    public function getCalendarRendezVousByPraticien(CalendarRendezVousDTO $calendarRendezVousDTO): array
    {
        try {
            $rdvs = $this->rdvRepository->getRendezVousByPraticienId($calendarRendezVousDTO->id);
            $calendar = [];
            foreach ($rdvs as $rdv) {
                if ($rdv->getDate() >= $calendarRendezVousDTO->date_debut && $rdv->getDate() <= $calendarRendezVousDTO->date_fin) {
                    $calendar[] = new RendezVousDTO($rdv);
                }
            }
            return $calendar;
        } catch (RepositoryEntityNotFoundException $e) {
            throw new RendezVousBadDataException($e->getMessage());
        } catch (RepositoryInternalServerError $e) {
            throw new RendezVousInternalServerError($e->getMessage());
        }
    }

}
