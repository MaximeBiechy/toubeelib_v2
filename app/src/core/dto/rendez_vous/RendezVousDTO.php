<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\dto\DTO;

class RendezVousDTO extends DTO
{
    protected string $id;
    protected \DateTimeImmutable $date;
    protected int $duree;
    protected string $praticienID;
    protected string $speciality;
    protected string $patientID;
    protected string $statut;

    public function __construct(RendezVous $rdv)
    {
        $this->id = $rdv->getId();
        $this->date = $rdv->getDate();
        $this->duree = $rdv->getDuree();
        $this->praticienID = $rdv->getPraticienID();
        $this->speciality = $rdv->getSpeciality();
        $this->patientID = $rdv->getPatientID();
        $this->statut = $rdv->getStatut();
    }



}