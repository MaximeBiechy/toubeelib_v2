<?php

namespace toubeelib\core\domain\entities\rendez_vous;

use toubeelib\core\domain\entities\Entity;

class RendezVous extends Entity
{
    private const STATUT_PREVU = "PREVU";
    private const STATUT_REALISE = "REALISE";
    private const STATUT_NON_HONORE = "NON_HONORE";
    private const STATUT_ANNULE = "ANNULE";
    private const STATUT_PAYE = "PAYE";
    protected \DateTimeImmutable $date;
    protected int $duree = 30;
    protected string $patientID;
    protected string $praticienID;
    protected string $speciality;
    protected string $statut;

    public function __construct(string $praticienID, string $patientID, string $speciality, string $date, string $statut = self::STATUT_PREVU)
    {
        $this->id = null;
        $this->date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);
        $this->praticienID = $praticienID;
        $this->patientID = $patientID;
        $this->speciality = $speciality;
        $this->statut = $statut;
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
        return $this->patientID;
    }

    public function setPatientID(string $patientID): void
    {
        $this->patientID = $patientID;
    }

    public function getPraticienID(): string
    {
        return $this->praticienID;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function annuler(): void
    {
        $this->statut = self::STATUT_ANNULE;
    }

    public function realiser(): void
    {
        if ($this->statut === self::STATUT_ANNULE) {
            throw new \Exception("Impossible de réaliser un rendez-vous annulé");
        }
        $this->statut = self::STATUT_REALISE;
    }

    public function nonHonore(): void
    {
        if ($this->statut === self::STATUT_ANNULE) {
            throw new \Exception("Impossible de marquer un rendez-vous annulé comme non honoré");
        }
        $this->statut = self::STATUT_NON_HONORE;
    }

    public function payer(): void
    {
        if ($this->statut === self::STATUT_ANNULE) {
            throw new \Exception("Impossible de payer un rendez-vous annulé");
        }
        $this->statut = self::STATUT_PAYE;
    }

    public function getSpeciality(): string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): void
    {
        $this->speciality = $speciality;
    }

}