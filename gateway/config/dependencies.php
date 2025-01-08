<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Container\ContainerInterface;
use toubeelib\application\actions\ConsultingAllPraticiensAction;

return [

    ClientInterface::class => function(ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://localhost:6080/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Origin' => 'http://localhost:6080'
            ]
        ]);
    },


    // ACTIONS
    ConsultingAllPraticiensAction::class => function (ContainerInterface $c) {
        return new ConsultingAllPraticiensAction($c->get(ClientInterface::class));
    }

];
