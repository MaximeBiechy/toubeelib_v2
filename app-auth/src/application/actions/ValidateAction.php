<?php

namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpUnauthorizedException;
use toubeelib\application\provider\auth\AuthProviderBeforeValidException;
use toubeelib\application\provider\auth\AuthProviderInterface;
use toubeelib\application\provider\auth\AuthProviderSignatureInvalidException;
use toubeelib\application\provider\auth\AuthProviderTokenExpiredException;
use toubeelib\application\renderer\JsonRenderer;

class ValidateAction extends AbstractAction
{
    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $token = $rq->getHeader('Authorization')[0] ?? null;
            if (empty($token)) {
                throw new HttpUnauthorizedException($rq, 'Token not found');
            }
            $token = str_replace('Bearer ', '', $token);
            $this->authProvider->getSignedInUser($token);
            return JsonRenderer::render($rs, 204);
        }catch (AuthProviderTokenExpiredException $e) {
            throw new HttpUnauthorizedException($rq, 'Token expired');
        }catch (AuthProviderBeforeValidException $e) {
            throw new HttpUnauthorizedException($rq, 'Token not yet valid');
        }catch (AuthProviderSignatureInvalidException $e) {
            throw new HttpUnauthorizedException($rq, 'Token signature invalid');
        }catch (AuthProviderTokenExpiredException $e) {
            throw new HttpUnauthorizedException($rq, 'Token expired');
        }

    }
}
