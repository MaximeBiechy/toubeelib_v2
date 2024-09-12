<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\dto\DTO;

class RendezVousDTO extends DTO
{
    protected string $id;
    protected \DateTimeImmutable $date;
    protected int $duree;
    protected string $praticienNom;
    protected string $praticienPrenom;
    protected string $praticienNumero;
    protected string $speciality;
    protected string $patientID;

    public function __construct(RendezVous $rdv, Praticien $praticien)
    {
        $this->id = $rdv->getId();
        $this->date = $rdv->getDate();
        $this->duree = $rdv->getDuree();
        $this->praticienNom = $praticien->getNom();
        $this->praticienPrenom = $praticien->getPrenom();
        $this->praticienNumero = $praticien->getTel();
        $this->speciality = $rdv->getSpeciality();
        $this->patientID = $rdv->getPatientID();
    }



}