<?php

namespace toubeelib\core\services\rdv;

class RDVPraticienNotFoundException extends \Exception
{
    public function __construct($message = "Praticien non trouvé")
    {
        parent::__construct($message);
    }
}