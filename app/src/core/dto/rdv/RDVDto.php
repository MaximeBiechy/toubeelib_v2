<?php

namespace toubeelib\core\dto\rdv;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\DTO;

class RDVDto extends DTO
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