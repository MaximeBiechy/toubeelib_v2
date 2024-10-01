<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\rendez_vous\CreateRendezVousDTO;
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
            $placeInputValidator = Validator::key('date', Validator::stringType()->notEmpty())
            ->key('duree', Validator::intVal()->notEmpty())
            ->key('praticienID', Validator::stringType()->notEmpty())
            ->key('patientID', Validator::stringType()->notEmpty())
            ->key('specialiteDM', Validator::stringType()->notEmpty());
            try{
                $placeInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }
            if(filter_var($data["date"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["date"] ){
                throw new HttpBadRequestException($rq, "Bad data format date");
            }
            if(filter_var($data["praticienID"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["praticienID"]){
                throw new HttpBadRequestException($rq, "Bad data format praticienID");
            }
            if(filter_var($data["patientID"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["patientID"]){
                throw new HttpBadRequestException($rq, "Bad data format patientID");
            }
            if(filter_var($data["specialiteDM"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["specialiteDM"]){
                throw new HttpBadRequestException($rq, "Bad data format specialiteDM");
            }

            $dto = new CreateRendezVousDTO($data["date"], $data["duree"], $data["praticienID"], $data["patientID"], $data["specialiteDM"]);

            $rdv = $this->rendezVousServiceInterface->creerRendezvous($dto);
            $urlPraticien = $routeParser->urlFor('praticien_id', ['ID-PRATICIEN' => $rdv->praticienID]);
            $urlPatient = $routeParser->urlFor('patient_id', ['ID-PATIENT' => $rdv->patientID]);
            $urlRDV = $routeParser->urlFor('rendez_vous_id', ['ID-RDV' => $rdv->id]);

            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "rdv" => $rdv,
                "links" => [
                    "self" => ['href' => $urlRDV],
                    "praticien" => ['href' => $urlPraticien],
                    "patient" => ['href' => $urlPatient]
                ]
            ];

            return JsonRenderer::render($rs, 201, $response);
        }
        catch( RendezVousPraticienNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}
