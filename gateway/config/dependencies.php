<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\AuthentificationAction;
use toubeelib\application\actions\PatientAction;
use toubeelib\application\actions\PraticienAction;
use toubeelib\application\actions\RendezVousAction;
use toubeelib\application\middlewares\Validation;

return [

    'ClientInterfacePraticiens' => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.praticiens.toubeelib/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    },
    'ClientInterfaceRDVS' => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.rdvs.toubeelib/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    },
    'ClientInterfacePatient' => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.patients.toubeelib/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    },
    'ClientInterfaceAuthentification' => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.auth.toubeelib/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    },


    // ACTIONS
    PraticienAction::class => function (ContainerInterface $c) {
        return new PraticienAction($c->get('ClientInterfacePraticiens'));
    },
    RendezVousAction::class => function (ContainerInterface $c) {
        return new RendezVousAction($c->get('ClientInterfaceRDVS'));
    },
    PatientAction::class => function (ContainerInterface $c) {
        return new PatientAction($c->get('ClientInterfacePatient'));
    },
    AuthentificationAction::class => function (ContainerInterface $c) {
        return new AuthentificationAction($c->get('ClientInterfaceAuthentification'));
    },

    // MIDDLEWARES
    Validation::class => function(ContainerInterface $c) {
        return new Validation(
            $c->get('ClientInterfaceAuthentification')
        );
    },

];
