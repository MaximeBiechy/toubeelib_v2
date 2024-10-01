<?php

namespace toubeelib\infrastructure\db;

use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;

class PDOPatientRepository implements PatientRepositoryInterface
{

    public function save(Patient $patient): string
    {
        // TODO: Implement save() method.
    }

    public function getPatientById(string $id): Patient
    {
        // TODO: Implement getPatientById() method.
    }
}