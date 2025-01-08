<?php

namespace toubeelib\application\actions;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class ConsultingAllPraticiensAction extends AbstractAction
{

    private ClientInterface $remote_praticiens_service;

    public function __construct(ClientInterface $client)
    {
        $this->remote_praticiens_service = $client;
    }


    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $response = $this->remote_praticiens_service->get('praticiens');
        } catch (ClientException $e ) {
            match($e->getCode()) {
                401 => throw new HttpUnauthorizedException($rq, $e->getMessage()),
                403 => throw new HttpForbiddenException($rq, $e->getMessage()),
                404 => throw new HttpNotFoundException($rq, $e->getMessage())
            };
        }

        return $response;
    }
}