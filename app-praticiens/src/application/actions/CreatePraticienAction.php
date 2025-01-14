<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\praticien\InputPraticienDTO;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInternalServerError;
use toubeelib\core\services\praticien\ServicePraticienInvalidDataException;

class CreatePraticienAction extends AbstractAction
{
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();



            $data = $rq->getParsedBody();
            $placeInputValidator = Validator::key('nom', Validator::stringType()->notEmpty())
                ->key('prenom', Validator::stringType()->notEmpty())
                ->key('adresse', Validator::stringType()->notEmpty())
                ->key('tel', Validator::stringType()->notEmpty())
                ->key('specialite', Validator::stringType()->notEmpty());
            try{
                $placeInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }
            if(filter_var($data["nom"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["nom"] ){
                throw new HttpBadRequestException($rq, "Bad data format nom");
            }
            if(filter_var($data["prenom"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["prenom"]){
                throw new HttpBadRequestException($rq, "Bad data format prenom");
            }
            if(filter_var($data["adresse"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["adresse"]){
                throw new HttpBadRequestException($rq, "Bad data format adresse");
            }
            if(filter_var($data["tel"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["tel"]){
                throw new HttpBadRequestException($rq, "Bad data format tel");
            }
            if(filter_var($data["specialite"],FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["specialite"]){
                throw new HttpBadRequestException($rq, "Bad data format specialite");
            }

            $dto = new InputPraticienDTO($data["nom"], $data["prenom"], $data["adresse"], $data["tel"], $data["specialite"]);
            $praticien = $this->servicePraticien->createPraticien($dto);
            $urlPraticien = $routeParser->urlFor('praticien_id', ['ID-PRATICIEN' => $praticien->id]);
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "praticien" => $praticien,
                "links" => [
                    "self" => ['href' => $urlPraticien],
                ]
            ];

            return JsonRenderer::render($rs, 201, $response);
        }catch (ServicePraticienInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (ServicePraticienInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }



    }
}