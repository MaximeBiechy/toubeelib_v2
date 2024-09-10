<?php

namespace toubeelib\core\dto\rdv;

use toubeelib\core\dto\DTO;

class CancelRDVDto extends DTO
{
    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

}