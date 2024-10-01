<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\rendez_vous\RendezVousNotFoundException;
use toubeelib\core\services\rendez_vous\RendezVousPraticienNotFoundException;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class CreateRendezVousAction extends AbstractAction{

    private RendezVousServiceInterface $rendezVousServiceInterface;

    public function __construct(RendezVousServiceInterface $rendezVousService){
        $this->rendezVousServiceInterface = $rendezVousService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args) : ResponseInterface{
        try{
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();

            $data = $rq->getParsedBody();
            $dto = new \toubeelib\core\dto\rendez_vous\CreateRendezVousDTO($data["date"], $data["duree"], $data["praticienID"], $data["patientID"], $data["specialiteDM"]);

            $rdv = $this->rendezVousServiceInterface->creerRendezvous($dto);

            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "rdv" => $rdv
            ];

            return JsonRenderer::render($rs, 201, $response);
        }
        catch( RendezVousPraticienNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}
