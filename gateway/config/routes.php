<?php
declare(strict_types=1);

use toubeelib\application\actions\ConsultingAllPraticiensAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/praticiens[/]', ConsultingAllPraticiensAction::class);

    return $app;
};
