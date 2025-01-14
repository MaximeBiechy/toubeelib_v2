<?php

namespace toubeelib\infrastructure\http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\dto\praticien\PraticienDTO;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticienInternalServerError;
use toubeelib\core\services\praticien\ServicePraticienInvalidDataException;

class ServicePraticienHTTP implements ServicePraticienInterface
{

    private ClientInterface $remote_praticiens_service;

    public function __construct(ClientInterface $client)
    {
        $this->remote_praticiens_service = $client;
    }

    public function getPraticienById(string $id): PraticienDTO
    {
        try {
            $response = $this->remote_praticiens_service->get("/praticiens/$id");
            $data = json_decode($response->getBody()->getContents(), true);
            $p = new Praticien($data['praticien']['nom'], $data['praticien']['prenom'], $data['praticien']['adresse'], $data['praticien']['tel']);
            $praticien = new PraticienDTO($p);
            return $praticien;

        } catch (ConnectException|ServerException $e) {
            throw new ServicePraticienInternalServerError($e->getMessage());
        } catch (ClientException $e) {
            match ($e->getCode()) {
                400 => throw new ServicePraticienInvalidDataException($e->getMessage()),
            };
        }
    }
}
