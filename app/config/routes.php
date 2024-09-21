<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\ConsultingPatientAction;
use toubeelib\application\actions\ConsultingPraticienAction;
use toubeelib\application\actions\ConsultingRendezVousAction;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);
    $app->get('/rdvs/{ID-RDV}', ConsultingRendezVousAction::class)->setName('rendez_vous_id');
    $app->get('/praticiens/{ID-PRATICIEN}', ConsultingPraticienAction::class)->setName('praticien_id');
    $app->get('/patients/{ID-PATIENT}', ConsultingPatientAction::class)->setName('patient_id');


    return $app;
};