<?php

namespace toubeelib\infrastructure\db;

use Ramsey\Uuid\Uuid;
use toubeelib\core\domain\entities\rendez_vous\RendezVous;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalServerError;

class PDORendezVousRepository implements RendezVousRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveRDV(RendezVous $rdv): string
    {
        try{
            if ($rdv->getID() !== null) {
                $stmt = $this->pdo->prepare("UPDATE rendez_vous SET date = :date, duree = :duree, patient_id = :patient_id, praticien_id = :praticien_id, specialite_id = :specialite_id, statut = :statut WHERE id = :id");
            }else{
                $id = Uuid::uuid4()->toString();
                $rdv->setID($id);
                $stmt = $this->pdo->prepare("INSERT INTO rendez_vous (id, date, duree, patient_id, praticien_id, specialite_id, statut) VALUES (:id, :date, :duree, :patient_id, :praticien_id, :specialite_id, :statut)");
            }
            $stmt->execute([
                'id' => $rdv->getID(),
                'date' => $rdv->getDate()->format('Y-m-d H:i:s'),
                'duree' => $rdv->getDuree(),
                'patient_id' => $rdv->getPatientID(),
                'praticien_id' => $rdv->getPraticienID(),
                'specialite_id' => $rdv->getSpeciality(),
                'statut' => $rdv->getStatut()
            ]);
            return $rdv->getID();
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while saving rendez-vous");
        }
    }

    public function getRDVById(string $id): RendezVous
    {
        try{
            $stmt = $this->pdo->prepare("SELECT * FROM rendez_vous WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $rdv = $stmt->fetch();
            if ($rdv === false) {
                throw new RepositoryEntityNotFoundException("Rendez-vous not found");
            }
            $rdva =  new RendezVous($rdv['praticien_id'], $rdv['patient_id'], $rdv['specialite_id'], $rdv['date'], $rdv['statut']);
            $rdva->setID($rdv['id']);
            return $rdva;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching rendez-vous");
        }

    }

    public function getRDVByPraticienId(string $praticienId): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM rendez_vous WHERE praticien_id = :praticien_id");
            $stmt->execute(['praticien_id' => $praticienId]);
            $rdvs = $stmt->fetchAll();
            $rdvsArray = [];
            foreach ($rdvs as $rdv) {
                $rdva = new RendezVous($rdv['praticien_id'], $rdv['patient_id'], $rdv['specialite_id'], $rdv['date']);
                $rdva->setID($rdv['id']);
                $rdvsArray[] = $rdva;
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching rendez-vous");
        }
    }

    public function getRendezVousByPatientId(string $id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM rendez_vous WHERE patient_id = :patient_id");
            $stmt->execute(['patient_id' => $id]);
            $rdvs = $stmt->fetchAll();
            $rdvsArray = [];
            foreach ($rdvs as $rdv) {
                $rdva = new RendezVous($rdv['praticien_id'], $rdv['patient_id'], $rdv['specialite_id'], $rdv['date']);
                $rdva->setID($rdv['id']);
                $rdvsArray[] = $rdva;
            }
            return $rdvsArray;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching rendez-vous");
        }
    }
}