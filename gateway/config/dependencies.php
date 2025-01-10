<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\PraticienAction;
use toubeelib\application\actions\RendezVousAction;

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


    // ACTIONS
    PraticienAction::class => function (ContainerInterface $c) {
        return new PraticienAction($c->get('ClientInterfacePraticiens'));
    },
    RendezVousAction::class => function (ContainerInterface $c) {
        return new RendezVousAction($c->get('ClientInterfaceRDVS'));
    },

];
