<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\patient\PatientService;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\patient\ServicePatientInvalidDataException;

class ConsultingPatientAction extends AbstractAction
{
    private PatientServiceInterface $patientServiceInterface;

    public function __construct(PatientServiceInterface $patientServiceInterface)
    {
        $this->patientServiceInterface = $patientServiceInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $patientId = $args['ID-PATIENT'];
            $patient = $this->patientServiceInterface->getPatientById($patientId);
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPatient = $routeParser->urlFor('patient_id', ['ID-PATIENT' => $patient->id]);
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "patient" => $patient,
                "links" => [
                    "self" => ['href' => $urlPatient]
                ]
            ];
            return JsonRenderer::render($rs, 200, $response);
        }catch (ServicePatientInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}