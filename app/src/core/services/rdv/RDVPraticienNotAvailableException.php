<?php

namespace toubeelib\core\services\rdv;

class RDVPraticienNotAvailableException extends \Exception
{

    public function __construct($message = "Le praticien n'est pas disponible à la date et à l'heure demandées")
    {
        parent::__construct($message);
    }
}