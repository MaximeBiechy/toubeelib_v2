<?php

namespace toubeelib\core\dto\patient;

use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\dto\DTO;

class PatientDTO extends DTO
{
    protected string $id;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;

    public function __construct(Patient $p) {
        $this->id = $p->getID();
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
    }

}