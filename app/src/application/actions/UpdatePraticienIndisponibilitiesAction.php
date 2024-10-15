<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\rendez_vous\CreateRendezVousDTO;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rendez_vous\RendezVousBadDataException;
use toubeelib\core\services\rendez_vous\rendezVousInternalServerError;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class UpdatePraticienIndisponibilitiesAction extends AbstractAction
{

    private RendezVousServiceInterface $rendezVousServiceInterface;
    private ServicePraticienInterface $servicePraticienInterface;

    public function __construct(RendezVousServiceInterface $rendezVousServiceInterface, ServicePraticienInterface $servicePraticienInterface) {
        $this->rendezVousServiceInterface = $rendezVousServiceInterface;
        $this->servicePraticienInterface = $servicePraticienInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $praticienID = $args['ID-PRATICIEN'];
        $debut = $rq->getQueryParams()['debut'] ?? null;
        $fin = $rq->getQueryParams()['fin'] ?? null;

        if ($debut === null || $fin === null) {
            throw new HttpNotFoundException("Paramètres manquants");
        }

        $debut = urldecode($debut);
        $fin = urldecode($fin);

        // ! Créer des rendez-vous pour chaque créneau horaire entre $debut et $fin
        $dateDebut = new \DateTimeImmutable($debut);
        $dateFin = new \DateTimeImmutable($fin);
        $interval = new \DateInterval('PT30M');
        $date = $dateDebut;
        while ($date < $dateFin) {
            $dto = new CreateRendezVousDTO($date->format('Y-m-d H:i:s'), 30, $praticienID, 'iiiiiiii-iiii-iiii-iiii-iiiiiiiiiiii', $this->servicePraticienInterface->getSpecialiteIdByPraticienId($praticienID));
            $this->rendezVousServiceInterface->creerRendezvous($dto);
            $date = $date->add($interval);
        }

        return JsonRenderer::render($rs, 200);

    }
}