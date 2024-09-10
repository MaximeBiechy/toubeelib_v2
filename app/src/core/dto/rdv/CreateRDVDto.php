<?php

namespace toubeelib\core\dto\rdv;

use toubeelib\core\dto\DTO;

class CreateRDVDto extends DTO
{
    protected \DateTimeImmutable $date;
    protected int $duree;
    protected string $praticienID;
    protected string $patientID;
    protected string $specialiteDM;
    protected string $statut;

    public function __construct(\DateTimeImmutable $date, string $duree, string $praticienID, string $patientID, string $specialiteDM, string $statut)
    {
        $this->date = $date;
        $this->duree = $duree;
        $this->praticienID = $praticienID;
        $this->patientID = $patientID;
        $this->specialiteDM = $specialiteDM;
        $this->statut = $statut;
    }

}