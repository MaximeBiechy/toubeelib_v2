<?php
    namespace toubeelib\core\services\rdv;

    use toubeelib\core\domain\entities\praticien;
    use toubeelib\core\domain\entities\patient;
    use toubeelib\core\domain\entities\rdv;

    use namespace toubeelib\core\services\praticien;

    use toubeelib\core\repositoryInterfaces;

    class ServiceRDV implements ServiceRDVInterface{

        private PraticienRepositoryInterface $PraticienRepository;
        private RdvRepositoryInterface $RDVRepository;

        public function __construct(PraticienRepositoryInterface $praticienRepository){
            $this->PraticienRepository = $PraticienRepository;
            $this->RDVRepository = $RDVRepository;

        }
    
        public function getRDVById(string $id): RDVDto{
        try {
            $current_rdv = $RDVRepository->getPraticienById($id);


            // protected string $id;
            // protected \DateTimeImmutable $date;
            // protected int $duree;
            // protected string $praticienID;
            // protected string $patientID;



            // return new RDVDto();

        } 
        catch(RepositoryEntityNotFoundException $e) {
            throw new RDVNotFoundException('Rendez-vous non-trouvé.');
        }
    }    
    }

?>