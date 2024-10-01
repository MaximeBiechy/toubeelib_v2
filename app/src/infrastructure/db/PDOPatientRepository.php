<?php

namespace toubeelib\infrastructure\db;

use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;

class PDOPatientRepository implements PatientRepositoryInterface
{

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Patient $patient): string
    {
        $sql = "INSERT INTO patients (nom, prenom, adresse, tel, id) VALUES (:nom, :prenom, :adresse, :tel, :id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nom', $patient->getNom());
        $stmt->bindValue(':prenom', $patient->getPrenom());
        $stmt->bindValue(':adresse', $patient->getAdresse());
        $stmt->bindValue(':tel', $patient->getTel());
        $stmt->bindValue(':id', $patient->getID());
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function getPatientById(string $id): Patient
    {
        $sql = "SELECT * FROM patients WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }
}