<?php

namespace src\Middleware;

use Google_Client;
use Google_Service_Oauth2;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class googleAuthMiddleware implements MiddlewareInterface
{
    private $googleClient;

    public function __construct(Google_Client $googleClient)
    {
        $this->googleClient = $googleClient;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $metodo = $request->getMethod();
        if($metodo === 'GET'){
            $_SESSION['redirecionar'] = $request->getUri()->getPath();
        }
        if ( (!isset($_SESSION['usuario']) && !$this->isGoogleAuthenticated() )|| $_SESSION['usuario']['ultimoAcesso'] < time() - 360) {
            $response = new Response();
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        $_SESSION['usuario']['ultimoAcesso'] = time();
//        $response = $handler->handle($request);
//        return $response;
        return $handler->handle($request);
    }

    private function isGoogleAuthenticated(): bool
    {
        if (isset($_SESSION['google_token'])) {
            $this->googleClient->setAccessToken($_SESSION['google_token']);

            if ($this->googleClient->isAccessTokenExpired()) {
                unset($_SESSION['google_token']);
                return false;
            }

            $googleService = new Google_Service_Oauth2($this->googleClient);
            $userInfo = $googleService->userinfo->get();

            $_SESSION['usuario'] = [
                'logado' => true,
                'ultimoAcesso' => time(),
                'codigo' => $userInfo->getId(),
                'nome' => $userInfo->getName(),
                'administrador' => false,
                'email' => $userInfo->getEmail()
            ];

            return true;
        }

        return false;
    }
}
