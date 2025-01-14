<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\CancelRendezVousAction;
use toubeelib\application\actions\ConsultingAllPraticiensAction;
use toubeelib\application\actions\ConsultingPatientAction;
use toubeelib\application\actions\ConsultingPatientRendezVousAction;
use toubeelib\application\actions\ConsultingPraticienAction;
use toubeelib\application\actions\ConsultingPraticienDisponibilitiesAction;
use toubeelib\application\actions\ConsultingRendezVousAction;
use toubeelib\application\actions\ConsultingRendezVousPraticienAction;
use toubeelib\application\actions\CreatePraticienAction;
use toubeelib\application\actions\CreateRendezVousAction;
use toubeelib\application\actions\RefreshAction;
use toubeelib\application\actions\SigninAction;
use toubeelib\application\actions\SignupPatientAction;
use toubeelib\application\actions\UpdatePraticienIndisponibilitiesAction;
use toubeelib\application\actions\UpdateRendezVousAction;
use toubeelib\application\actions\UpdateRendezVousEtatAction;
use toubeelib\application\middlewares\Auth;
use toubeelib\application\middlewares\AuthzPatient;
use toubeelib\application\middlewares\AuthzPraticien;
use toubeelib\application\middlewares\AuthzRendezVous;

return function( \Slim\App $app):\Slim\App {
    // Les praticiens
    $app->get('/praticiens[/]', ConsultingAllPraticiensAction::class)->setName('praticiens');
    $app->post('/praticiens[/]', CreatePraticienAction::class)->setName('create_praticien_id');
    $app->get('/praticiens/{ID-PRATICIEN}[/]', ConsultingPraticienAction::class)->setName('praticien_id');

    return $app;
};
