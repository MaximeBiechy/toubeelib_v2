<?php

namespace toubeelib\core\dto\rendez_vous;

use toubeelib\core\dto\DTO;

class CalendarRendezVousDTO extends DTO
{
    protected string $id;
    protected \DateTimeImmutable $date_debut;
    protected \DateTimeImmutable $date_fin;
    
    public function __construct(string $id, \DateTimeImmutable $date_debut, \DateTimeImmutable $date_fin)
    {
        $this->id = $id;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }

}