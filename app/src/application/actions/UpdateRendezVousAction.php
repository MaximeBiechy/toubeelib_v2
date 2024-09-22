<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\rendez_vous\UpdatePatientRendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdateSpecialityRendezVousDTO;
use toubeelib\core\services\rendez_vous\RendezVousNotFoundException;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class UpdateRendezVousAction extends AbstractAction
{
    private RendezVousServiceInterface $rendezVousServiceInterface;

    public function __construct(RendezVousServiceInterface $rendezVousServiceInterface)
    {
        $this->rendezVousServiceInterface = $rendezVousServiceInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $id = $args['ID-RDV'];
            $data = $rq->getParsedBody();
            if (isset($data['speciality'])){
                $dto = new UpdateSpecialityRendezVousDTO($id,$data['speciality']);
                $this->rendezVousServiceInterface->updateSpecialityRendezVous($dto);
            } elseif (isset($data['patientID'])){
                $dto = new UpdatePatientRendezVousDTO($id,$data['patientID']);
                $this->rendezVousServiceInterface->updatePatientRendezVous($dto);
            } else {
                throw new HttpBadRequestException($rq, 'Invalid request');
            }

        }catch (RendezVousNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $rdv = $this->rendezVousServiceInterface->consultingRendezVous($id);
        $urlPraticien = $routeParser->urlFor('praticien_id', ['ID-PRATICIEN' => $rdv->praticienID]);
        $urlPatient = $routeParser->urlFor('patient_id', ['ID-PATIENT' => $rdv->patientID]);
        $urlRDV = $routeParser->urlFor('rendez_vous_id', ['ID-RDV' => $rdv->id]);
        $response = [
            "type" => "resource",
            "locale" => "fr-FR",
            "rendez_vous" => $rdv,
            "links" => [
                "self" => ['href' => $urlRDV] ,
                "praticien" => ['href' => $urlPraticien],
                "patient" => ['href' => $urlPatient]
            ]

        ];
        return JsonRenderer::render($rs, 201, $response);


    }
}