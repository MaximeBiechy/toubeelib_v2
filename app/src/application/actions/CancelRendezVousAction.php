<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use toubeelib\application\actions\AbstractAction;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;
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
        } catch (RepositoryEntityNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }


        return $rs->withStatus(204);
    }
}