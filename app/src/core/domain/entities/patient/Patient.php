<?php

namespace toubeelib\core\domain\entities\patient;

use toubeelib\core\domain\entities\Entity;

class Patient extends Entity
{
    protected ?string $ID;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;

    public function __construct(string $ID, string $nom, string $prenom, string $adresse, string $tel)
    {
        $this->ID = $ID;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
    }

    public function getID(): string
    {
        return $this->ID;
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

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setTel(string $tel): void
    {
        $this->tel = $tel;
    }

}