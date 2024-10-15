<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\provider\auth\AuthProviderInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\services\auth\AuthentificationServiceBadDataException;
use toubeelib\core\services\auth\AuthentificationServiceInternalServerErrorException;
use toubeelib\core\services\auth\AuthentificationServiceNotFoundException;

class SigninAction extends AbstractAction {
    private AuthProviderInterface $authnProviderInterface;

    public function __construct(AuthProviderInterface $authnProviderInterface){
        $this->authnProviderInterface = $authnProviderInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface{
        $data = $rq->getParsedBody();
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new HttpBadRequestException($rq, 'missing email or password');
        }
        $email = $data['email'];
        $password = $data['password'];
        try {
            $authDTO = $this->authnProviderInterface->signin(new CredentialsDTO($email, $password));
            $res = [
                $authDTO->token,
                $authDTO->token_refresh
            ];
            return JsonRenderer::render($rs, 201, $res);
        } catch (AuthentificationServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (AuthentificationServiceBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (AuthentificationServiceInternalServerErrorException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}