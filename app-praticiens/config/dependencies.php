<?php


use Psr\Container\ContainerInterface;
use toubeelib\application\actions\CancelRendezVousAction;
use toubeelib\application\actions\ConsultingAllPraticiensAction;
use toubeelib\application\actions\ConsultingPatientRendezVousAction;
use toubeelib\application\actions\ConsultingPraticienDisponibilitiesAction;
use toubeelib\application\actions\ConsultingRendezVousPraticienAction;
use toubeelib\application\actions\CreatePraticienAction;
use toubeelib\application\actions\CreateRendezVousAction;
use toubeelib\application\actions\ConsultingPatientAction;
use toubeelib\application\actions\ConsultingPraticienAction;
use toubeelib\application\actions\ConsultingRendezVousAction;
use toubeelib\application\actions\RefreshAction;
use toubeelib\application\actions\SigninAction;
use toubeelib\application\actions\UpdatePraticienIndisponibilitiesAction;
use toubeelib\application\actions\UpdateRendezVousAction;
use toubeelib\application\actions\UpdateRendezVousEtatAction;
use toubeelib\application\middlewares\Auth;
use toubeelib\application\middlewares\AuthzPatient;
use toubeelib\application\middlewares\AuthzPraticien;
use toubeelib\application\middlewares\AuthzRendezVous;
use toubeelib\application\provider\auth\AuthProviderInterface;
use toubeelib\application\provider\auth\JWTAuthProvider;
use toubeelib\application\provider\auth\JWTManager;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\services\auth\AuthentificationService;
use toubeelib\core\services\auth\AuthentificationServiceInterface;
use toubeelib\core\services\patient\AuthorizationPatientService;
use toubeelib\core\services\patient\AuthorizationPatientServiceInterface;
use toubeelib\core\services\patient\PatientService;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\praticien\AuthorizationPraticienService;
use toubeelib\core\services\praticien\AuthorizationPraticienServiceInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rendez_vous\AuthorizationRendezVousService;
use toubeelib\core\services\rendez_vous\AuthorizationRendezVousServiceInterface;
use toubeelib\core\services\rendez_vous\RendezVousService;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;
use toubeelib\infrastructure\db\PDOAuthRepository;
use toubeelib\infrastructure\db\PDOPatientRepository;
use toubeelib\infrastructure\db\PDOPraticienRepository;
use toubeelib\infrastructure\db\PDORendezVousRepository;
use toubeelib\infrastructure\repositories\ArrayPatientRepository;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\infrastructure\repositories\ArrayRendezVousRepository;
use toubeelib\application\actions\SignupPatientAction;

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
        $data = parse_ini_file($c->get('praticien.ini'));
        $pdo_praticien = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $pdo_praticien->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_praticien;
    },

    // Repositories
    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPraticienRepository($c->get('pdo_praticien'));
    },

    // Services
    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
    },
    AuthorizationPraticienServiceInterface::class => function (ContainerInterface $c) {
        return new AuthorizationPraticienService();
    },

    // Actions
    ConsultingPraticienAction::class => function (ContainerInterface $c) {
        return new ConsultingPraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },
    ConsultingAllPraticiensAction::class => function (ContainerInterface $c) {
        return new ConsultingAllPraticiensAction(
            $c->get(ServicePraticienInterface::class)
        );
    },
    CreatePraticienAction::class => function (ContainerInterface $c) {
        return new CreatePraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },

];
