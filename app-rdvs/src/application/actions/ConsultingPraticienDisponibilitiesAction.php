<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\rendez_vous\DisponibilityPraticienRendezVousDTO;
use toubeelib\core\services\rendez_vous\RendezVousBadDataException;
use toubeelib\core\services\rendez_vous\rendezVousInternalServerError;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class ConsultingPraticienDisponibilitiesAction extends AbstractAction
{

    private RendezVousServiceInterface $rendezVousServiceInterface;

    public function __construct(RendezVousServiceInterface $rendezVousServiceInterface)
    {
        $this->rendezVousServiceInterface = $rendezVousServiceInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $debut = $rq->getQueryParams()['debut'] ?? null;
            $fin = $rq->getQueryParams()['fin'] ?? null;
            $duree = $rq->getQueryParams()['duree'] ?? null;

            if ($debut === null || $fin === null || $duree === null) {
                throw new HttpNotFoundException($rq, 'Paramètres manquants');
            }

            $debut = urldecode($debut);
            $fin = urldecode($fin);

            $praticienID = $args['ID-PRATICIEN'];
            $dto = new DisponibilityPraticienRendezVousDTO(
                $praticienID,
                \DateTimeImmutable::createFromFormat('Y-m-d H:i', $debut),
                \DateTimeImmutable::createFromFormat('Y-m-d H:i', $fin),
                (int)$duree
            );

            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPraticien = $routeParser->urlFor('praticien_id', ['ID-PRATICIEN' => $praticienID]);
            $urlDispobilites = $routeParser->urlFor('praticien_id_disponibilites', ['ID-PRATICIEN' => $praticienID]);

            $disponibilities = $this->rendezVousServiceInterface->getDisponibilityPraticienRendezVous($dto);
            $result = [
                "type" => "collection",
                "locale" => "fr-FR",
                "disponibilities" => $disponibilities,
                "links" => [
                    "self" => ["href" => $urlDispobilites],
                    "praticien" => ["href" => $urlPraticien]
                ]
            ];


            return JsonRenderer::render($rs, 200, $result);
        }catch (RendezVousBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }catch (RendezVousInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

    }
}