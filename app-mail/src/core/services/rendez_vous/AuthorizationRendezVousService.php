<?php

namespace toubeelib\core\services\rendez_vous;

use toubeelib\core\repositoryInterfaces\RendezVousRepositoryInterface;

class AuthorizationRendezVousService implements AuthorizationRendezVousServiceInterface
{
    protected RendezVousRepositoryInterface $rendezVousRepository;

    public function __construct(RendezVousRepositoryInterface $rendezVousRepository)
    {
        $this->rendezVousRepository = $rendezVousRepository;
    }

    public function isGranted(string $user_id, int $operation, string $ressource_id, int $role): bool
    {
        $rdv = $this->rendezVousRepository->getRendezVousById($ressource_id);

        if($role === 0 && $user_id === $rdv->getPatientId()){
            return true;
        }else if($role === 10 && $user_id === $rdv->getPraticienID()) {
            return true;
        }else
            return false;
    }
}