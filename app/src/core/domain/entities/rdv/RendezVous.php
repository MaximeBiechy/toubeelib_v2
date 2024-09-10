<?php

namespace toubeelib\core\domain\entities\rdv;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\domain\entities\praticien\Praticien;

class RendezVous extends Entity
{
    protected string $id;
    protected \DateTimeImmutable $date;
    protected int $duree;
    protected Patient $patient;
    protected string $praticienID;
    protected string $statut;

    public function __construct(string $id, \DateTimeImmutable $date, int $duree, Patient $patient, string $praticienID, string $statut)
    {
        $this->id = $id;
        $this->date = $date;
        $this->duree = $duree;
        $this->patient = $patient;
        $this->praticienID = $praticienID;
        $this->statut = $statut;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getPatientID(): string
    {
        return $this->patient->getID();
    }

    public function getPraticienID(): string
    {
        return $this->praticienID;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setPatient(Patient $patient): void
    {
        $this->patient = $patient;
    }

}