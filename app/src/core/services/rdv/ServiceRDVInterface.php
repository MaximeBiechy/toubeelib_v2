<?php

namespace toubeelib\core\services\rdv;

use toubeelib\core\dto\rdv\CreateRDVDto;
use toubeelib\core\dto\rdv\RDVDto;

interface ServiceRDVInterface
{
    public function creerRendezvous(CreateRDVDto $createRDVDTO): RDVDto;
}