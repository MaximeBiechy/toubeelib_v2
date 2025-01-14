<?php
declare(strict_types=1);

use toubeelib\application\actions\PatientAction;
use toubeelib\application\actions\PraticienAction;
use toubeelib\application\actions\RendezVousAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/praticiens[/]', PraticienAction::class);
    $app->get('/praticiens/{ID-PRATICIEN}[/]', PraticienAction::class);
    $app->get('/praticiens/{ID-PRATICIEN}/disponibilites[/]', RendezVousAction::class)->setName('praticien_id_disponibilites');
    $app->get('/praticiens/{ID-PRATICIEN}/rdvs[/]', RendezVousAction::class)->setName('praticien_id_rdvs');
    $app->post('/praticiens/{ID-PRATICIEN}/indisponibilites[/]', RendezVousAction::class)->setName('praticien_id_indisponibilites');

    // Les rendez-vous
    $app->get('/rdvs/{ID-RDV}[/]', RendezVousAction::class)->setName('rendez_vous_id');
    $app->post('/rdvs[/]', RendezVousAction::class)->setName('create_rendez_vous_id');
    $app->patch('/rdvs/{ID-RDV}[/]', RendezVousAction::class)->setName('update_rendez_vous_id');
    $app->patch('/rdvs/{ID-RDV}/state', RendezVousAction::class)->setName('update_rendez_vous_id_etat');
    $app->delete('/rdvs/{ID-RDV}[/]', RendezVousAction::class)->setName('cancel_rendez_vous_id');

    $app->get('/patients/{ID-PATIENT}[/]', PatientAction::class)->setName('patient_id');


    return $app;
};
