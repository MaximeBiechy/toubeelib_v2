<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\patient\ServicePatientInternalServerError;
use toubeelib\core\services\patient\ServicePatientInvalidDataException;

class ConsultingPatientRendezVousAction extends AbstractAction
{
    private PatientServiceInterface $patientService;

    public function __construct(PatientServiceInterface $patientService)
    {
        $this->patientService = $patientService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $patientId = $args['ID-PATIENT'];
            $patient = $this->patientService->getPatientById($patientId);
            $rendezVous = $this->patientService->getRendezVousByPatientId($patientId);
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPatient = $routeParser->urlFor('patient_id', ['ID-PATIENT' => $patient->id]);
            $urlSelf = $routeParser->urlFor('patient_id_rdvs', ['ID-PATIENT' => $patient->id]);
            $rendezVous = array_map(function($rdv) use ($routeParser) {
                $urlRdv = $routeParser->urlFor('rendez_vous_id', ['ID-RDV' => $rdv->id]);
                return [
                    "id" => $rdv->id,
                    "date" => $rdv->date,
                    "duree" => $rdv->duree,
                    "praticienID" => $rdv->praticienID,
                    "speciality" => $rdv->speciality,
                    "patientID" => $rdv->patientID,
                    "statut" => $rdv->statut,
                    "links" => [
                        "self" => ['href' => $urlRdv]
                    ]
                ];
            }, $rendezVous);
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "rendezVous" => $rendezVous,
                "links" => [
                    "self" => ['href' => $urlSelf],
                    "patient" => ['href' => $urlPatient]
                ]
            ];
            return JsonRenderer::render($rs, 200, $response);
        }catch (ServicePatientInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }catch (ServicePatientInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}