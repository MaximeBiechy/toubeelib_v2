<?php

namespace toubeelib\infrastructure\db;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;

class PDOPraticienRepository implements PraticienRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSpecialiteById(string $id): Specialite
    {
        $query = $this->pdo->prepare('SELECT * FROM specialite WHERE id = :id');
        $query->execute(['id' => $id]);
        $specialite = $query->fetch();
        return new Specialite($specialite['id'], $specialite['label'], $specialite['description']);
    }

    public function save(Praticien $praticien): string
    {
        $query = $this->pdo->prepare('INSERT INTO praticien (id, nom, prenom, adresse, telephone, specialite_id) VALUES (:id, :nom, :prenom, :adresse, :telephone, :specialite_id)');
        $query->execute([
            'id' => $praticien->getID(),
            'nom' => $praticien->getNom(),
            'prenom' => $praticien->getPrenom(),
            'adresse' => $praticien->getAdresse(),
            'telephone' => $praticien->getTel(),
            'specialite_id' => $praticien->getSpecialite()
        ]);
        return $praticien->getID();
    }

    public function getPraticienById(string $id): Praticien
    {
        $query = $this->pdo->prepare('SELECT * FROM praticien WHERE id = :id');
        $query->execute(['id' => $id]);
        $praticien = $query->fetch();
        return new Praticien($praticien['nom'], $praticien['prenom'], $praticien['adresse'], $praticien['telephone']);
    }
}