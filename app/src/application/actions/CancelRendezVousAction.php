<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\core\services\rendez_vous\rendezVousInternalServerError;
use toubeelib\core\services\rendez_vous\RendezVousNotFoundException;
use toubeelib\core\services\rendez_vous\RendezVousServiceInterface;

class CancelRendezVousAction extends AbstractAction
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
            $this->rendezVousServiceInterface->annulerRendezvous($id);
        } catch (RendezVousNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (RendezVousInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }


        return $rs->withStatus(204);
    }
}