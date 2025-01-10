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
    'pdo_patient' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('patient.ini'));
        $pdo_patient = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $pdo_patient->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_patient;
    },
    'pdo_rendez_vous' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('rdv.ini'));
        $pdo_rendez_vous = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $pdo_rendez_vous->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_rendez_vous;
    },
    'pdo_auth' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('auth.ini'));
        $pdo_auth = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $pdo_auth->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_auth;
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
    AuthRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOAuthRepository($c->get('pdo_auth'));
    },

    // Providers
    AuthProviderInterface::class => function (ContainerInterface $c) {
        return new JWTAuthProvider(
            $c->get(AuthentificationServiceInterface::class),
            new JWTManager
        );
    },

    // Services
    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
    },
    PatientServiceInterface::class => function (ContainerInterface $c) {
        return new PatientService(
            $c->get(PatientRepositoryInterface::class),
            $c->get(RendezVousServiceInterface::class),
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
    AuthentificationServiceInterface::class => function (ContainerInterface $c) {
        return new AuthentificationService(
            $c->get(AuthRepositoryInterface::class),
        );
    },
    AuthorizationRendezVousServiceInterface::class => function (ContainerInterface $c) {
        return new AuthorizationRendezVousService(
            $c->get(RendezVousRepositoryInterface::class)
        );
    },
    AuthorizationPatientServiceInterface::class => function (ContainerInterface $c) {
        return new AuthorizationPatientService();
    },
    AuthorizationPraticienServiceInterface::class => function (ContainerInterface $c) {
        return new AuthorizationPraticienService();
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
    ConsultingPatientRendezVousAction::class => function (ContainerInterface $c) {
        return new ConsultingPatientRendezVousAction(
            $c->get(PatientServiceInterface::class)
        );
    },
    UpdateRendezVousEtatAction::class => function (ContainerInterface $c) {
        return new UpdateRendezVousEtatAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },
    ConsultingAllPraticiensAction::class => function (ContainerInterface $c) {
        return new ConsultingAllPraticiensAction(
            $c->get(ServicePraticienInterface::class)
        );
    },
    ConsultingRendezVousPraticienAction::class => function (ContainerInterface $c) {
        return new ConsultingRendezVousPraticienAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },
    CreatePraticienAction::class => function (ContainerInterface $c) {
        return new CreatePraticienAction(
            $c->get(ServicePraticienInterface::class)
        );
    },
    SigninAction::class => function (ContainerInterface $c) {
        return new SigninAction(
            $c->get(AuthProviderInterface::class)
        );
    },
    RefreshAction::class => function (ContainerInterface $c) {
        return new RefreshAction(
            $c->get(AuthentificationServiceInterface::class)
        );
    },

    // Middlewares
    Auth::class => function (ContainerInterface $c) {
        return new Auth(
            $c->get(AuthProviderInterface::class)
        );
    },
    AuthzPatient::class => function (ContainerInterface $c) {
        return new AuthzPatient(
            $c->get(AuthorizationPatientServiceInterface::class)
        );
    },
    AuthzPraticien::class => function (ContainerInterface $c) {
        return new AuthzPraticien(
            $c->get(AuthorizationPraticienServiceInterface::class)
        );
    },
    AuthzRendezVous::class => function (ContainerInterface $c) {
        return new AuthzRendezVous(
            $c->get(AuthorizationRendezVousServiceInterface::class)
        );
    },
    UpdatePraticienIndisponibilitiesAction::class => function (ContainerInterface $c) {
        return new UpdatePraticienIndisponibilitiesAction(
            $c->get(RendezVousServiceInterface::class),
            $c->get(ServicePraticienInterface::class)
        );
    },
    SignupPatientAction::class => function (ContainerInterface $c) {
        return new SignupPatientAction(
            $c->get(PatientServiceInterface::class)
        );
    },

];
