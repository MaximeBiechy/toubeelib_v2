<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\praticien\ServicePraticienInterface;
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
        }

    }
}