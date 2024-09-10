<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\core\dto\rdv\CreateRDVDto;

interface RdvRepositoryInterface
{
    public function saveRDV(RendezVous $rdv): string;
    public function getRDVById(string $id): RendezVous;
    public function cancelRDV(string $id): void;

}