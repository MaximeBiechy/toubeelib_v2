<?php

namespace toubeelib\core\services\patient;

use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\dto\patient\InputPatientDTO;
use toubeelib\core\dto\patient\PatientDTO;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class PatientService implements PatientServiceInterface
{
    private PatientRepositoryInterface $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function createPatient(InputPatientDTO $p): PatientDTO
    {
        try{
            $patient = new Patient($p->nom, $p->prenom, $p->adresse, $p->tel);
            $this->patientRepository->save($patient);

            return new PatientDTO($patient);
        } catch (RepositoryInternalServerError $e) {
            throw new ServicePatientInternalServerError($e->getMessage());
        }

    }

    public function getPatientById(string $id): PatientDTO
    {
        try {
            $patient = $this->patientRepository->getPatientById($id);
            return new PatientDTO($patient);
        } catch(RepositoryEntityNotFoundException $e) {
            throw new ServicePatientInvalidDataException('invalid Patient ID');
        } catch(RepositoryInternalServerError $e) {
            throw new ServicePatientInternalServerError($e->getMessage());
        }

    }
}