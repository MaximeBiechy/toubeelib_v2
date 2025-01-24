<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\dto\DTO;

class UpdatePatientRendezVousDTO extends DTO
{
    protected string $id;
    protected string $patientID;

    public function __construct(string $id, string $patientID)
    {
        $this->id = $id;
        $this->patientID = $patientID;
    }

}