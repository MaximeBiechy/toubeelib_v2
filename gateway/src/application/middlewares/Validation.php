<?php

namespace toubeelib\application\middlewares;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Request;

class Validation
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke(Request $rq, RequestHandlerInterface $next)
    {
        try{
            $auth = $rq->getHeader('Authorization') ?? null;
            if (empty($auth)) {
                throw new HttpUnauthorizedException($rq, 'Token not found');
            }
            $options = ['headers' => ['Authorization' => $auth]];
            $this->client->request('POST', '/validate', $options);
            $rs = $next->handle($rq);
            return $rs;
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
    }

}
