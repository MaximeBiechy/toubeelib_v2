<?php

namespace toubeelib\infrastructure\db;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;

class PDOPraticienRepository implements PraticienRepositoryInterface
{

    public function getSpecialiteById(string $id): Specialite
    {
        // TODO: Implement getSpecialiteById() method.
    }

    public function save(Praticien $praticien): string
    {
        // TODO: Implement save() method.
    }

    public function getPraticienById(string $id): Praticien
    {
        // TODO: Implement getPraticienById() method.
    }
}