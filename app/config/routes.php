<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\CancelRendezVousAction;
use toubeelib\application\actions\ConsultingPatientAction;
use toubeelib\application\actions\ConsultingPraticienAction;
use toubeelib\application\actions\ConsultingRendezVousAction;
use toubeelib\application\actions\UpdateRendezVousAction;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    // Les rendez-vous
    $app->get('/rdvs/{ID-RDV}', ConsultingRendezVousAction::class)->setName('rendez_vous_id');
    $app->post('/rdvs/{ID-RDV}', ConsultingRendezVousAction::class)->setName('create_rendez_vous_id');
    $app->put('/rdvs/{ID-RDV}', UpdateRendezVousAction::class)->setName('update_rendez_vous_id');
    $app->delete('/rdvs/{ID-RDV}', CancelRendezVousAction::class)->setName('cancel_rendez_vous_id'); // ! Route pour annuler un rendez-vous

    // Les praticiens
    $app->get('/praticiens/{ID-PRATICIEN}', ConsultingPraticienAction::class)->setName('praticien_id');

    // Les patients
    $app->get('/patients/{ID-PATIENT}', ConsultingPatientAction::class)->setName('patient_id');


    return $app;
};