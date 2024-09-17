<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\rendez_vous\RendezVousNotFoundException;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class ConsultingRendezVousAction extends AbstractAction
{
    private RendezVousServiceInterface $rendezVousService;

    public function __construct(RendezVousServiceInterface $rendezVousService)
    {
        $this->rendezVousService = $rendezVousService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $rdvId = $args['ID-RDV'];

            $rdv = $this->rendezVousService->consultingRendezVous($rdvId);

            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "rendez_vous" => $rdv,
                "links" => [
                    "self" => "/rdvs/" . $rdv->id,
                    "praticien" => "/praticiens/" . $rdv->praticienID,
                    "patient" => "/patients/" . $rdv->patientID
                ]

            ];
            return JsonRenderer::render($rs, 200, $response);

        }catch (RendezVousNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

    }
}