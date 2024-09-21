<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\services\praticien\ServicePraticienInterface;

class ConsultingPraticienAction extends AbstractAction
{

    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $praticienId = $args['ID-PRATICIEN'];
        $praticien = $this->servicePraticien->getPraticienById($praticienId);
        $routeContext = RouteContext::fromRequest($rq);
        $routeParser = $routeContext->getRouteParser();
        $urlPraticien = $routeParser->urlFor('praticien', ['ID-PRATICIEN' => $praticien->id]);
        $response = [
            "type" => "resource",
            "locale" => "fr-FR",
            "praticien" => $praticien,
            "links" => [
                "self" => $urlPraticien,
            ]

        ];
        return JsonRenderer::render($rs, 200, $response);
    }
}