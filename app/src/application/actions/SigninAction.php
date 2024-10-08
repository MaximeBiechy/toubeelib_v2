<?php
namespace toubeelib\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use toubeelib\core\dto\auth\CredentialsDTO;
use toubeelib\core\services\auth\AuthentificationServiceInterface;use toubeelib\core\services\auth\AuthentificationServiceNotFoundException;

class SigninAction extends AbstractAction {
  private AuthentificationServiceInterface $authentificationServiceInterface;

  public function __construct(AuthentificationServiceInterface $authentificationServiceInterface){
    $this->authentificationServiceInterface = $authentificationServiceInterface;
  }

  public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface{

    try {
      $data = $rq->getParsedBody();
      $email = $data["email"];
      $password = $data["password"];

      $credentialsDTO = new CredentialsDTO($email, $password);

      $this->authentificationServiceInterface->byCredentials($credentialsDTO);

      return $rs->withStatus(201);
    }

    catch (AuthentificationServiceNotFoundException $e) {
      return $rs->withStatus(401);
    }
  }
}

?>
