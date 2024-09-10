<?php

namespace toubeelib\core\services\rdv;

class RDVSpecialitePraticienDifferentException extends \Exception
{

    public function __construct($message = "La spécialité du praticien ne correspond pas à la spécialité du rendez-vous demandé")
    {
        parent::__construct($message);
    }
}