<?php


use Psr\Container\ContainerInterface;
use toubeelib\application\actions\ConsultingPraticienAction;
use toubeelib\application\actions\ConsultingRendezVousAction;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\services\patient\PatientService;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rendez_vous\RendezVousService;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;
use toubeelib\infrastructure\repositories\ArrayPatientRepository;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRendezVousRepository;

return [
    'log.prog.level' => \Monolog\Level::Debug,
    'log.prog.name' => 'njp.program.log',
    'log.prog.file' => __DIR__ . '/log/njp.program.error.log',
    'prog.logger' => function(ContainerInterface $c) {
        $logger = new \Monolog\Logger($c->get('log.prog.name'));
        $logger->pushHandler(
            new \Monolog\Handler\StreamHandler(
                $c->get('log.prog.file'),
                $c->get('log.prog.level')));
        return $logger;
    },
    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new ArrayPraticienRepository();
    },
    PatientRepositoryInterface::class => function (ContainerInterface $c) {
        return new ArrayPatientRepository();
    },
    RendezVousRepositoryInterface::class => function (ContainerInterface $c) {
        return new ArrayRendezVousRepository();
    },
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
            $c->get('prog.logger')
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

];