<?php

namespace toubeelib\application\actions;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class AuthentificationAction extends AbstractAction
{

    private ClientInterface $remote_auth_service;

    public function __construct(ClientInterface $client)
    {
        $this->remote_auth_service = $client;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $method = $rq->getMethod();
        $path = $rq->getUri()->getPath();
        $options = ['query' => $rq->getQueryParams()];
        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            $options['json'] = $rq->getParsedBody();
        }
        $auth = $rq->getHeader('Authorization') ?? null;
        if (!empty($auth)) {
            $options['headers'] = ['Authorization' => $auth];
        }
        try {
            $response = $this->remote_auth_service->request($method, $path, $options);
        } catch (ConnectException|ServerException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (ClientException $e) {
            match ($e->getCode()) {
                400 => throw new HttpBadRequestException($rq, $e->getMessage()),
                401 => throw new HttpUnauthorizedException($rq, $e->getMessage()),
                403 => throw new HttpForbiddenException($rq, $e->getMessage()),
                404 => throw new HttpNotFoundException($rq, $e->getMessage()),
            };
        }

        return $response;
    }
}
