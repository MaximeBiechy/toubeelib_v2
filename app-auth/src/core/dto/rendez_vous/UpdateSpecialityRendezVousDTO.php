<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\dto\DTO;

class UpdateSpecialityRendezVousDTO extends DTO
{
    protected string $id;
    protected string $speciality;

    public function __construct(string $id, string $speciality)
    {
        $this->id = $id;
        $this->speciality = $speciality;
    }

}