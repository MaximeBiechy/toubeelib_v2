<?php


use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\CancelRendezVousAction;
use toubeelib\application\actions\ConsultingPraticienDisponibilitiesAction;
use toubeelib\application\actions\ConsultingRendezVousAction;
use toubeelib\application\actions\ConsultingRendezVousPraticienAction;
use toubeelib\application\actions\CreateRendezVousAction;
use toubeelib\application\actions\UpdateRendezVousAction;
use toubeelib\application\actions\UpdateRendezVousEtatAction;
use toubeelib\application\provider\auth\AuthProviderInterface;
use toubeelib\application\provider\auth\JWTAuthProvider;
use toubeelib\application\provider\auth\JWTManager;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;
use toubeelib\core\services\auth\AuthentificationServiceInterface;
use toubeelib\core\services\mail\MailServiceInterface;
use toubeelib\core\services\mail\MailService;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rendez_vous\RendezVousService;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;
use toubeelib\infrastructure\db\PDOAuthRepository;
use toubeelib\infrastructure\db\PDOPatientRepository;
use toubeelib\infrastructure\db\PDOPraticienRepository;
use toubeelib\infrastructure\db\PDORendezVousRepository;
use toubeelib\infrastructure\http\ServicePatientHTTP;
use toubeelib\infrastructure\http\ServicePraticienHTTP;

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
    'ClientInterfacePraticiens' => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.praticiens.toubeelib/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    },
    'ClientInterfacePatients' => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.patients.toubeelib/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    },

    // Repositories
    MailServiceInterface::class => function (ContainerInterface $c) {
        return new MailService();
    },
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
        return new ServicePraticienHTTP($c->get('ClientInterfacePraticiens'));
    },
    PatientServiceInterface::class => function (ContainerInterface $c) {
        return new ServicePatientHTTP($c->get('ClientInterfacePatients'));
    },
    RendezVousServiceInterface::class => function (ContainerInterface $c) {
        return new RendezVousService(
            $c->get(ServicePraticienInterface::class),
            $c->get(RendezVousRepositoryInterface::class),
            $c->get(PatientServiceInterface::class),
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
    UpdateRendezVousEtatAction::class => function (ContainerInterface $c) {
        return new UpdateRendezVousEtatAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },
    ConsultingRendezVousPraticienAction::class => function (ContainerInterface $c) {
        return new ConsultingRendezVousPraticienAction(
            $c->get(RendezVousServiceInterface::class)
        );
    },

];
