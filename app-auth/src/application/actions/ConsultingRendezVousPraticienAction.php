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
use toubeelib\core\dto\rendez_vous\CalendarRendezVousDTO;
use toubeelib\core\services\rendez_vous\RendezVousBadDataException;
use toubeelib\core\services\rendez_vous\rendezVousInternalServerError;
use toubeelib\core\services\rendez_vous\RendezVousNotFoundException;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class ConsultingRendezVousPraticienAction extends AbstractAction
{
    private RendezVousServiceInterface $rendezVousServiceInterface;

    public function __construct(RendezVousServiceInterface $rendezVousServiceInterface)
    {
        $this->rendezVousServiceInterface = $rendezVousServiceInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $id = $args['ID-PRATICIEN'];
            $uuidValidator = new Validator();
            if (!$uuidValidator->validate($args['ID-PRATICIEN'])) {
                throw new HttpBadRequestException($rq, "Invalid UUID format.");
            }
            $data = $rq->getQueryParams();
            $date_debut = $data['date_debut'] ?? null;
            $date_fin = $data['date_fin'] ?? null;
            if ($date_debut === null || $date_fin === null) {
                $calendar = $this->rendezVousServiceInterface->getRendezVousByPraticien($id);
            }else{
                $date_debut = urldecode($date_debut);
                $date_debut = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $date_debut);
                $date_fin = urldecode($date_fin);
                $date_fin = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $date_fin);
                $calendar = $this->rendezVousServiceInterface->getCalendarRendezVousByPraticien(new CalendarRendezVousDTO($id, $date_debut, $date_fin));
            }
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPraticien = $routeParser->urlFor('praticien_id', ['ID-PRATICIEN' => $id]);
            $calendar = array_map(function($rendezVous) use ($routeParser){
                $urlRendezVous = $routeParser->urlFor('rendez_vous_id', ['ID-RDV' => $rendezVous->id]);
                return [
                    "id" => $rendezVous->id,
                    "date" => $rendezVous->date,
                    "duree" => $rendezVous->duree,
                    "patient" => $rendezVous->patientID,
                    "praticien" => $rendezVous->praticienID,
                    "specialite" => $rendezVous->speciality,
                    "statut" => $rendezVous->statut,
                    "links" => [
                        "self" => ["href" => $urlRendezVous]
                    ]
                ];
            }, $calendar);
            $result = [
                "type" => "collection",
                "locale" => "fr-FR",
                "disponibilities" => $calendar,
                "links" => [
                    "self" => ["href" => $urlPraticien],
                ]
            ];
            return JsonRenderer::render($rs,200, $result);
        } catch (RendezVousBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch ( RendezVousInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (RendezVousNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}