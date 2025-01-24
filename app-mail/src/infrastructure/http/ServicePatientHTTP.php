<?php

namespace toubeelib\infrastructure\http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\dto\patient\PatientDTO;
use toubeelib\core\services\patient\PatientServiceInterface;
use toubeelib\core\services\patient\ServicePatientInternalServerError;
use toubeelib\core\services\patient\ServicePatientInvalidDataException;

class ServicePatientHTTP implements PatientServiceInterface
{

    private ClientInterface $remote_patients_service;

    public function __construct(ClientInterface $client)
    {
        $this->remote_patients_service = $client;
    }

    public function getPatientById(string $id): PatientDTO
    {
        try {
            $response = $this->remote_patients_service->get("/patients/$id");
            $data = json_decode($response->getBody()->getContents(), true);
            $p = new Patient($data['patient']['nom'], $data['patient']['prenom'], $data['patient']['adresse'], $data['patient']['tel']);
            $p->setID($data['patient']['id']);
            $patient = new PatientDTO($p);
            return $patient;

        } catch (ConnectException|ServerException $e) {
            throw new ServicePatientInternalServerError($e->getMessage());
        } catch (ClientException $e) {
            match ($e->getCode()) {
                400 => throw new ServicePatientInvalidDataException($e->getMessage()),
            };
        }
    }
}
