<?php

namespace toubeelib\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayRendezVousRepository implements RendezVousRepositoryInterface
{
    private array $rdvs = [];

    public function __construct() {
            $r1 = new RendezVous('p1', 'pa1', 'A','2024-09-02 09:00' );
            $r1->setID('r1');
            $r2 = new RendezVous('p1', 'pa1', 'A','2024-09-02 10:00');
            $r2->setID('r2');
            $r3 = new RendezVous('p2', 'pa1', 'A','2024-09-02 09:30');
            $r3->setID('r3');

        $this->rdvs  = ['r1'=> $r1, 'r2'=>$r2, 'r3'=> $r3 ];
    }

    public function saveRDV(RendezVous $rdv): string
    {
        if ($rdv->getID() !== null && isset($this->rdvs[$rdv->getID()])) {
            $this->rdvs[$rdv->getID()] = $rdv;
        }else{
            $id = Uuid::uuid4()->toString();
            $rdv->setID($id);
            $this->rdvs[$id] = $rdv;
        }
        return $rdv->getID();
    }

    public function getRDVById(string $id): RendezVous
    {
        if (!isset($this->rdvs[$id])) {
            throw new RepositoryEntityNotFoundException("Rendez-vous not found");
        }
        return $this->rdvs[$id];
    }

    public function getRDVByPraticienId(string $praticienId): array
    {
        $rdvs = [];
        foreach ($this->rdvs as $rdv) {
            if ($rdv->getPraticienID() == $praticienId) {
                $rdvs[] = $rdv;
            }
        }
        return $rdvs;
    }

}