<?php

namespace toubeelib\core\services\rendez_vous;

class RendezVousPraticienNotAvailableException extends \Exception
{

    public function __construct($message = "Le praticien n'est pas disponible à la date et à l'heure demandées")
    {
        parent::__construct($message);
    }
}