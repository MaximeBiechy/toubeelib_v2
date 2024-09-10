<?php

namespace toubeelib\core\services\praticien;

use toubeelib\core\dto\praticien\InputPraticienDTO;
use toubeelib\core\dto\praticien\PraticienDTO;
use toubeelib\core\dto\praticien\SpecialiteDTO;

interface ServicePraticienInterface
{

    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;


}