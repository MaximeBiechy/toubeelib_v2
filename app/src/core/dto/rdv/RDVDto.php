<?php

namespace toubeelib\core\dto\rdv;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\DTO;

class RDVDto extends DTO
{
    protected string $id;
    protected \DateTimeImmutable $date;
    protected int $duree;
    protected string $praticienID;
    protected string $patientID;

    public function __construct(RendezVous $rdv, )
    {
        $this->id = $rdv->getId();
        $this->date = $rdv->getDate();
        $this->duree = $rdv->getDuree();
        $this->praticienID = $rdv->getPraticienID();
        $this->patientID = $rdv->getPatientID();
    }

}