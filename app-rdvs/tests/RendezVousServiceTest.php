<?php


use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\domain\entities\praticien\Specialite;

class RendezVousServiceTest extends \PHPUnit\Framework\TestCase
{
    public function testconsultingRDV()
    {


    }

    public function test()
    {
        $pdo = new \toubeelib\infrastructure\db\PDORendezVousRepository(new PDO('pgsql:host=toubeelib.db;dbname=toubee_rdvs', 'root', 'root'));
        $aa = $pdo->getRendezVousById('800c5358-0c1c-41cc-a0c2-a7729edebce2');

    }


}