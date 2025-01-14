<?php

namespace toubeelib\core\services\rendez_vous;

class RendezVousPraticienNotFoundException extends \Exception
{
    public function __construct($message = "Praticien non trouvé")
    {
        parent::__construct($message);
    }
}