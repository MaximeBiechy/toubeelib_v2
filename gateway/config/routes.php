<?php
declare(strict_types=1);

use toubeelib\application\actions\PraticienAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/praticiens[/]', PraticienAction::class);
    $app->get('/praticiens/{ID-PRATICIEN}[/]', PraticienAction::class);
    $app->get('/praticiens/{ID-PRATICIEN}/rdvs[/]', PraticienAction::class);

    return $app;
};
