<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\Usuarios as Usuario;

class loginController
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }
    public function login(Request $request, Response $response, $args)
    {
        return \Slim\Views\Twig::fromRequest($request)->render($response, 'login.twig', [
            'actionLogin' => '/login',
        ]);
    }

    public function logar(Request $request, Response $response, $args)
    {
        $view = \Slim\Views\Twig::fromRequest($request);
        $post = $request->getParsedBody();
        if (isset($post['username']) && isset($post['password'])) {
            $username = $post['username'];
            $password = $post['password'];
            $usario = new Usuario();
            if(isset($_SESSION['redirecionar'])){
                $destino = $_SESSION['redirecionar'];
                unset($_SESSION['redirecionar']);
            } else {
                $destino = '/';
            }
            if ($usario->logarUsuario($username, $password)) {
                $_SESSION['usuario'] = [
                    'logado' => true,
                    'ultimoAcesso' => time(),
                    'codigo' => $usario->getCodUsuario(),
                    'nome' => $usario->getNome(),
                    'administrador' => $usario->getEAdministrador(),
                ];
                return $response->withHeader('Location', $destino)->withStatus(302);
            } else {
//                $response->getBody()->write('Usuário ou senha inválidos!');
                return $view->render($response, 'login.twig', [
                    'erro' => 'mensagem',
                    'corpoMensagem' => 'Usuário ou senha inválidos!']);
            }
        } else {
//            $response->getBody()->write('Usuário ou senha inválidos!');
            return $view->render($response, 'login.twig', [
                'erro' => 'mensagem',
                'corpoMensagem' => 'Usuário ou senha inválidos!']);
        }
        return $response;
        // TODO - Implementar a lógica de sessão
    }

    public function logout(Request $request, Response $response, $args)
    {
        // TODO - Implementar a lógica de sessão
        session_destroy();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function googleLogin(Request $request, Response $response) {
        // Configuração do cliente do Google Sign-In
        $client = new Google_Client([
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI
        ]);

        // Configuração da URL autorizada
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);
        $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
        $client->setApprovalPrompt('force');

        // Cria o URL de autenticação do Google
        $authUrl = $client->createAuthUrl();

        // Redireciona para a página de autenticação do Google
        return $response->withRedirect($authUrl);
    }

    public function googleCallback(Request $request, Response $response) {
        // Configuração do cliente do Google Sign-In
        $client = new Google_Client([
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI
        ]);

        // Verifica se o usuário está autenticado com o Google Sign-In
        if ($request->getQueryParam('code')) {
            $client->authenticate($request->getQueryParam('code'));

            // Salva o token de acesso do usuário para uso futuro
            $_SESSION['access_token'] = $client->getAccessToken();

            // Redireciona para a página principal da aplicação
            if($_SESSION['redirecionar']) {
                $redirecionar = $_SESSION['redirecionar'];
                return $response->withRedirect($redirecionar);
            } else
                return $response->withRedirect('/');
        } else {
            // Se não houver um código de autenticação, redireciona para a página de autenticação do Google
            return $response->withRedirect($client->createAuthUrl());
        }
    }
}