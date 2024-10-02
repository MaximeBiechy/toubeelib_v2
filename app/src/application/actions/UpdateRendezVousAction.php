<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\rendez_vous\UpdatePatientRendezVousDTO;
use toubeelib\core\dto\rendez_vous\UpdateSpecialityRendezVousDTO;
use toubeelib\core\services\rendez_vous\RendezVousBadDataException;
use toubeelib\core\services\rendez_vous\rendezVousInternalServerError;
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
            $placeInputValidator = Validator::key('speciality', Validator::stringType()->notEmpty(), false)
            ->key('patientID', Validator::stringType()->notEmpty(), false);
            try{
                $placeInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }


            if (isset($data['speciality'])){
                if ((filter_var($data['speciality'],
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS)!== $data['speciality'])
                ) {
                    throw new HttpBadRequestException($rq, 'Bad data format speciality');
                }
                $dto = new UpdateSpecialityRendezVousDTO($id,$data['speciality']);
                $this->rendezVousServiceInterface->updateSpecialityRendezVous($dto);
            }
            if (isset($data['patientID']) ){
                if ((filter_var($data['patientID'],
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS)!== $data['patientID'])
                ) {
                    throw new HttpBadRequestException($rq, 'Bad data format patientID');
                }
                $dto = new UpdatePatientRendezVousDTO($id,$data['patientID']);
                $this->rendezVousServiceInterface->updatePatientRendezVous($dto);
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
                    "patient" => ['href' => $urlPatient],
                    "update" => ['href' => $urlRDV, 'method' => 'PATCH']
                ]

            ];
            return JsonRenderer::render($rs, 201, $response);
        }catch (RendezVousBadDataException $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (RendezVousNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (RendezVousInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }




    }
}