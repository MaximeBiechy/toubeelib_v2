<?php

namespace toubeelib\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ArrayPraticienRepository implements PraticienRepositoryInterface
{

    const SPECIALITES = [
        'A' => [
            'ID' => 'A',
            'label' => 'Dentiste',
            'description' => 'Spécialiste des dents'
        ],
        'B' => [
            'ID' => 'B',
            'label' => 'Ophtalmologue',
            'description' => 'Spécialiste des yeux'
        ],
        'C' => [
            'ID' => 'C',
            'label' => 'Généraliste',
            'description' => 'Médecin généraliste'
        ],
        'D' => [
            'ID' => 'D',
            'label' => 'Pédiatre',
            'description' => 'Médecin pour enfants'
        ],
        'E' => [
            'ID' => 'E',
            'label' => 'Médecin du sport',
            'description' => 'Maladies et trausmatismes liés à la pratique sportive'
        ],
    ];

    private array $praticiens = [];

    public function __construct()
    {
        $this->praticiens['p1'] = new Praticien('Dupont', 'Jean', 'nancy', '0123456789');
        $this->praticiens['p1']->setSpecialite(new Specialite('A', 'Dentiste', 'Spécialiste des dents'));
        $this->praticiens['p1']->setID('p1');

        $this->praticiens['p2'] = new Praticien('Durand', 'Pierre', 'vandeuve', '0123456789');
        $this->praticiens['p2']->setSpecialite(new Specialite('B', 'Ophtalmologue', 'Spécialiste des yeux'));
        $this->praticiens['p2']->setID('p2');

        $this->praticiens['p3'] = new Praticien('Martin', 'Marie', '3lassou', '0123456789');
        $this->praticiens['p3']->setSpecialite(new Specialite('C', 'Généraliste', 'Médecin généraliste'));
        $this->praticiens['p3']->setID('p3');

    }

    public function getSpecialiteById(string $id): Specialite
    {

        $specialite = self::SPECIALITES[$id] ??
            throw new RepositoryEntityNotFoundException("Specialite $id not found");

        return new Specialite($specialite['ID'], $specialite['label'], $specialite['description']);
    }

    public function save(Praticien $praticien): string
    {
        if ($praticien->getID() !== null && isset($this->praticiens[$praticien->getID()])) {
            $this->praticiens[$praticien->getID()] = $praticien;

        }else{
            $ID = Uuid::uuid4()->toString();
            $praticien->setID($ID);
            $this->praticiens[$ID] = $praticien;
        }
        return $praticien->getID();
    }

    public function getPraticienById(string $id): Praticien
    {
        $praticien = $this->praticiens[$id] ??
            throw new RepositoryEntityNotFoundException("Praticien $id not found");

        return $praticien;
    }

    public function getAllPraticiens(): array
    {
        return $this->praticiens;
    }

    public function searchPraticiens(string $search): array
    {
        $result = [];
        foreach ($this->praticiens as $praticien) {
            if (stripos($praticien->getNom(), $search) !== false || stripos($praticien->getPrenom(), $search) !== false) {
                $result[] = $praticien;
            }
        }
        return $result;
    }

}