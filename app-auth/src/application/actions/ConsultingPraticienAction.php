<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Rfc4122\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInternalServerError;
use toubeelib\core\services\praticien\ServicePraticienInvalidDataException;

class ConsultingPraticienAction extends AbstractAction
{

    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $praticienId = $args['ID-PRATICIEN'];
            $uuidValidator = new Validator();
            if (!$uuidValidator->validate($args['ID-PRATICIEN'])) {
                throw new HttpBadRequestException($rq, "Invalid UUID format.");
            }
            $praticien = $this->servicePraticien->getPraticienById($praticienId);
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPraticien = $routeParser->urlFor('praticien_id', ['ID-PRATICIEN' => $praticien->id]);
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "praticien" => $praticien,
                "links" => [
                    "self" => ['href' => $urlPraticien]
                ]

            ];
            return JsonRenderer::render($rs, 200, $response);
        }catch (ServicePraticienInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }catch (ServicePraticienInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

    }
}