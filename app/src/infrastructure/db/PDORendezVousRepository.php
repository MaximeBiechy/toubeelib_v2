<?php

namespace toubeelib\infrastructure\db;

use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PDORendezVousRepository implements RendezVousRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveRDV(RendezVous $rdv): string
    {
        $stmt = $this->pdo->prepare("INSERT INTO rendez_vous (id, date, duree, patient_id, praticien_id, speciality, statut) VALUES (:id, :date, :duree, :patient_id, :praticien_id, :speciality, :statut)");
        $stmt->execute([
            'id' => $rdv->getID(),
            'date' => $rdv->getDate()->format('Y-m-d H:i'),
            'duree' => $rdv->getDuree(),
            'patient_id' => $rdv->getPatientID(),
            'praticien_id' => $rdv->getPraticienID(),
            'speciality' => $rdv->getSpeciality(),
            'statut' => $rdv->getStatut()
        ]);
        return $rdv->getID();
    }

    public function getRDVById(string $id): RendezVous
    {
        $stmt = $this->pdo->prepare("SELECT * FROM rendez_vous WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $rdv = $stmt->fetch();
        if ($rdv === false) {
            throw new RepositoryEntityNotFoundException("Rendez-vous not found");
        }
        return new RendezVous($rdv['praticien_id'], $rdv['patient_id'], $rdv['speciality'], $rdv['date']);
    }

    public function getRDVByPraticienId(string $praticienId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM rendez_vous WHERE praticien_id = :praticien_id");
        $stmt->execute(['praticien_id' => $praticienId]);
        $rdvs = $stmt->fetchAll();
        $rdvsArray = [];
        foreach ($rdvs as $rdv) {
            $rdvsArray[] = new RendezVous($rdv['praticien_id'], $rdv['patient_id'], $rdv['speciality'], $rdv['date']);
        }
        return $rdvsArray;
    }
}