<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\dto\DTO;

class CreateRendezVousDTO extends DTO
{
    protected \DateTimeImmutable $date;
    protected int $duree;
    protected string $praticienID;
    protected string $patientID;
    protected string $specialiteDM;

    public function __construct(\DateTimeImmutable $date, string $duree, string $praticienID, string $patientID, string $specialiteDM)
    {
        $this->date = $date;
        $this->duree = $duree;
        $this->praticienID = $praticienID;
        $this->patientID = $patientID;
        $this->specialiteDM = $specialiteDM;
    }

}