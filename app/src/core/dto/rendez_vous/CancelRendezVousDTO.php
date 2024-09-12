<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\dto\DTO;

class CancelRendezVousDTO extends DTO
{
    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

}