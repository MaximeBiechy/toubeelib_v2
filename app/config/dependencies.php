<?php


use Psr\Container\ContainerInterface;
use toubeelib\application\actions\CancelRendezVousAction;
use toubeelib\application\actions\ConsultingPraticienDisponibilitiesAction;
use toubeelib\application\actions\CreateRendezVousAction;
use toubeelib\application\actions\ConsultingPatientAction;
use toubeelib\application\actions\ConsultingPraticienAction;
use toubeelib\application\actions\ConsultingRendezVousAction;
use toubeelib\application\actions\UpdateRendezVousAction;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\services\patient\PatientService;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rendez_vous\RendezVousService;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;
use toubeelib\infrastructure\db\PDOPatientRepository;
use toubeelib\infrastructure\db\PDOPraticienRepository;
use toubeelib\infrastructure\db\PDORendezVousRepository;
use toubeelib\infrastructure\repositories\ArrayPatientRepository;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRendezVousRepository;

return [

    // Logger
    'log.prog.level' => \Monolog\Level::Debug,
    'log.prog.name' => 'njp.program.log',
    'log.prog.file' => __DIR__ . '/log/njp.program.error.log',
    'prog.logger' => function (ContainerInterface $c) {
        $logger = new \Monolog\Logger($c->get('log.prog.name'));
        $logger->pushHandler(
            new \Monolog\Handler\StreamHandler(
                $c->get('log.prog.file'),
                $c->get('log.prog.level')));
        return $logger;
    },

    'pdo_praticien' => function (ContainerInterface $c) {
        $pdo_praticien = new PDO('postgres:host=toubeelib.db;dbname=toubee_praticien', 'root', 'root');
        $pdo_praticien->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_praticien;
    },
    'pdo_patient' => function (ContainerInterface $c) {
        $pdo_patient = new PDO('postgres:host=toubeelib.db;dbname=toubee_patient', 'root', 'root');
        $pdo_patient->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_patient;
    },
    'pdo_rendez_vous' => function (ContainerInterface $c) {
        $pdo_rendez_vous = new PDO('postgres:host=toubeelib.db;dbname=toubee_rdvs', 'root', 'root');
        $pdo_rendez_vous->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_rendez_vous;
    },

    // Repositories
    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPraticienRepository($c->get('pdo_praticien'));
    },
    PatientRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPatientRepository($c->get('pdo_patient'));
    },
    RendezVousRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDORendezVousRepository($c->get('pdo_rendez_vous'));
    },

    // Services
    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
    },
    PatientServiceInterface::class => function (ContainerInterface $c) {
        return new PatientService(
            $c->get(PatientRepositoryInterface::class)
        );
    },
    RendezVousServiceInterface::class => function (ContainerInterface $c) {
        return new RendezVousService(
            $c->get(PraticienRepositoryInterface::class),
            $c->get(RendezVousRepositoryInterface::class),
            $c->get(PatientRepositoryInterface::class),
            $c->get('prog.logger')
        );
    },

    // Actions
    CreateRendezVousAction::class => function (ContainerInterface $c) {
        return new CreateRendezVousAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },
    ConsultingRendezVousAction::class => function (ContainerInterface $c) {
        return new ConsultingRendezVousAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },
    ConsultingPraticienAction::class => function (ContainerInterface $c) {
        return new ConsultingPraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },
    ConsultingPatientAction::class => function (ContainerInterface $c) {
        return new ConsultingPatientAction(
            $c->get(PatientServiceInterface::class)
        );
    },
    UpdateRendezVousAction::class => function (ContainerInterface $c) {
        return new UpdateRendezVousAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },
    CancelRendezVousAction::class => function (ContainerInterface $c) {
        return new CancelRendezVousAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },
    ConsultingPraticienDisponibilitiesAction::class => function (ContainerInterface $c) {
        return new ConsultingPraticienDisponibilitiesAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },

];