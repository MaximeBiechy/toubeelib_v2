<?php

namespace toubeelib\core\dto\praticien;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\dto\DTO;

class PraticienDTO extends DTO
{
    protected string $id;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $specialite_id;
    protected string $specialite_label;

    public function __construct(Praticien $p)
    {
        $this->id = $p->getID();
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
        $this->specialite_id = $p->specialite->id;
        $this->specialite_label = $p->specialite->label;
    }
}