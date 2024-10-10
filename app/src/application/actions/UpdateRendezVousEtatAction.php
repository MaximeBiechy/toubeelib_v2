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
use toubeelib\core\dto\rendez_vous\UpdateSpecialityRendezVousDTO;
use toubeelib\core\services\rendez_vous\RendezVousBadDataException;
use toubeelib\core\services\rendez_vous\rendezVousInternalServerError;
use toubeelib\core\services\rendez_vous\RendezVousNotFoundException;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class UpdateRendezVousEtatAction extends AbstractAction
{
    private RendezVousServiceInterface $rendezVousServiceInterface;

    public function __construct(RendezVousServiceInterface $rendezVousServiceInterface)
    {
        $this->rendezVousServiceInterface = $rendezVousServiceInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $data = $rq->getParsedBody();
            $id = $args['ID-RDV'];
            $placeInputValidator = Validator::key('state', Validator::stringType()->notEmpty());
            try{
                $placeInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }


            if ((filter_var($data['state'],
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS)!== $data['state'])
            ) {
                throw new HttpBadRequestException($rq, 'Bad data format speciality');
            }
            if (!$data['state'] || !in_array($data['state'], ['REALISE', 'NON_HONORE', 'PAYE'])) {
                throw new HttpBadRequestException($rq, 'Bad data format state');
            }
            switch ($data['state']) {
                case 'REALISE':
                    $this->rendezVousServiceInterface->honorRendezVous($id);
                    break;
                case 'NON_HONORE':
                    $this->rendezVousServiceInterface->nonHonorRendezVous($id);
                    break;
                case 'PAYE':
                    $this->rendezVousServiceInterface->payRendezVous($id);
                    break;
            }

            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $rdv = $this->rendezVousServiceInterface->consultingRendezVous($id);
            $urlPraticien = $routeParser->urlFor('praticien_id', ['ID-PRATICIEN' => $rdv->praticienID]);
            $urlPatient = $routeParser->urlFor('patient_id', ['ID-PATIENT' => $rdv->patientID]);
            $urlRDV = $routeParser->urlFor('rendez_vous_id', ['ID-RDV' => $rdv->id]);
            $urlRDVState = $routeParser->urlFor('update_rendez_vous_id_etat', ['ID-RDV' => $rdv->id]);
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "rendez_vous" => $rdv,
                "links" => [
                    "self" => ['href' => $urlRDV] ,
                    "praticien" => ['href' => $urlPraticien],
                    "patient" => ['href' => $urlPatient],
                    "update" => ['href' => $urlRDV, 'method' => 'PATCH'],
                    "etat" => ['href' => $urlRDVState, 'method' => 'PATCH']
                ]

            ];
            return JsonRenderer::render($rs, 201, $response);
        }catch (RendezVousBadDataException $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }catch (RendezVousInternalServerError $e){
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }catch (RendezVousNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

    }
}