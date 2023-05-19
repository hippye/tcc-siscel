<?php
namespace src\middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use src\model\Eleitor;

class authMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!isset($_SESSION['idVotacao']['logado']) && ($_SESSION['idVotacao']['ultimoAcesso'] < time() - 120)) {
            $_SESSION['redirecionar'] = $request->getUri()->getPath();
            $response = new \Slim\Psr7\Response();
            $response = $response->withHeader('Location', '/votacao/login')->withStatus(302);
            return $response;
        }
        $_SESSION['idVotacao']['ultimoAcesso'] = time();
        $eleitor = new Eleitor();
        $eleitor->buscarEleitorPorIdVotacao($_SESSION['idVotacao']['id']);
        $eleitor->atualizarToken($_SESSION['idVotacao']['token']);
        $response = $handler->handle($request);
        return $response;
    }
}
