<?php


use Psr\Container\ContainerInterface;
use toubeelib\application\actions\ConsultingPatientAction;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\services\patient\PatientService;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\infrastructure\db\PDOPatientRepository;

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

    'pdo_patient' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('patient.ini'));
        $pdo_patient = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $pdo_patient->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_patient;
    },


    // Repositories
    PatientRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPatientRepository($c->get('pdo_patient'));
    },

    // Services
    PatientServiceInterface::class => function (ContainerInterface $c) {
        return new PatientService(
            $c->get(PatientRepositoryInterface::class),
        );
    },

    // Actions
    ConsultingPatientAction::class => function (ContainerInterface $c) {
        return new ConsultingPatientAction(
            $c->get(PatientServiceInterface::class)
        );
    },

];
