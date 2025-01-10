<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\PraticienAction;

return [

    ClientInterface::class => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://api.toubeelib/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    },


    // ACTIONS
    PraticienAction::class => function (ContainerInterface $c) {
        return new PraticienAction($c->get(ClientInterface::class));
    }

];
