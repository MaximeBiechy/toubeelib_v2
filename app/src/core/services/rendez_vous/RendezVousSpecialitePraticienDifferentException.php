<?php

namespace toubeelib\core\services\rendez_vous;

class RendezVousSpecialitePraticienDifferentException extends \Exception
{

    public function __construct($message = "La spécialité du praticien ne correspond pas à la spécialité du rendez-vous demandé")
    {
        parent::__construct($message);
    }
}