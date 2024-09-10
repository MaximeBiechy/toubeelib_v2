<?php

namespace toubeelib\core\domain\entities\praticien;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\praticien\PraticienDTO;

class Praticien extends Entity
{
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected ?Specialite $specialite = null; // version simplifiée : une seule spécialité

    public function __construct(string $nom, string $prenom, string $adresse, string $tel)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
    }


    public function setSpecialite(Specialite $specialite): void
    {
        $this->specialite = $specialite;
    }

    public function toDTO(): PraticienDTO
    {
        return new PraticienDTO($this);
    }

    public function getSpecialite(): string
    {
        return $this->specialite->label;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getTel(): string
    {
        return $this->tel;
    }
}