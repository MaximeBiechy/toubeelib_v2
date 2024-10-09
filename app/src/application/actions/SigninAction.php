<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use toubeelib\application\provider\auth\AuthProviderInterface;
use toubeelib\application\renderer\JsonRenderer;
use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\services\auth\AuthentificationServiceBadDataException;
use toubeelib\core\services\auth\AuthentificationServiceNotFoundException;

class SigninAction extends AbstractAction {
  private AuthProviderInterface $authnProviderInterface;

  public function __construct(AuthProviderInterface $authnProviderInterface){
    $this->authnProviderInterface = $authnProviderInterface;
  }

  public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface{

    try {
      $data = $rq->getParsedBody();
      $email = $data["email"];
      $password = $data["password"];

      //!: A changer par la suite :
      $credentialsDTO = new CredentialsDTO($email, $password);

      $this->authnProviderInterface->signin($email, $password);

      return JsonRenderer::render($rs, 201);
    }

    catch (AuthentificationServiceNotFoundException $e) {
      throw new HttpNotFoundException($rq, $e->getMessage());
    }
    catch(AuthentificationServiceBadDataException $e){
      throw new HttpBadRequestException($rq, $e->getMessage());
    }
  }
}