<?php


use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;

class RendezVousServiceTest extends \PHPUnit\Framework\TestCase
{
    public function testconsultingRDV()
    {

    }

    public function testcreerRendezvous()
    {

    }

    public function testannulerRendezvous()
    {

    }

    public function testGetDisponibilityPraticienRDV()
    {
        $service = new toubeelib\core\services\rendez_vous\RendezVousRendezVousService(new \toubeelib\infrastructure\repositories\ArrayPraticienRepository(), new \toubeelib\infrastructure\repositories\ArrayRendezVousRepository(), new \Slim\Logger());
        $dto = new \toubeelib\core\dto\rendez_vous\DisponibilityPraticienRendezVousDTO('p1', new \DateTimeImmutable('2024-09-02 08:00'), new \DateTimeImmutable('2024-09-03 08:00'), 30);
        $rep = $service->getDisponibilityPraticienRendezVous($dto);
        $this->assertEquals(16, count($rep));
        $this->assertEquals(new \DateTimeImmutable('2024-09-02 09:30'), $rep[0]);
    }

    public function testupdateRDV()
    {

    }

    public function testupdateSpecialityRDV()
    {

    }

    public function testupdatePatientRDV()
    {

    }

}