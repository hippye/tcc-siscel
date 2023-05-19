<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\Usuarios as Usuario;


class usuarioController
{

    public function listar(Request $request, Response $response, $args)
    {
        unset($_SESSION['aba']);
        $usuario = new Usuario();
        $usuarios = $usuario->listarUsuarios();
        $erro = '';
        $mensagem = '';
        if(isset($_SESSION['erro']) && $_SESSION['erro'] == 'mensagem') {
            $erro = $_SESSION['erro'];
            $mensagem = $_SESSION['corpoMensagem'];
            unset($_SESSION['erro']);
            unset($_SESSION['corpoMensagem']);
        }
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'listarUsuarios.twig', [
            'raiz' => $request->getUri(),
            'usuarios' => $usuarios,
            'actionUsuario' => '/usuario',
            'erro' => $erro,
            'corpoMensagem' => $mensagem,
        ]);
    }

    public function alterar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $post = $request->getParsedBody();
        $usuario = new Usuario($id);
        $usuario->setNome($post['nome']);
        $usuario->setNomeDeUsuario(limpaMascara($post['cpf']));
        $usuario->setCpf($post['cpf']);
        $usuario->setEmail($post['email']);
        if (isset($post['administrador'])) {
            $usuario->setEAdministrador(1);
        } else {
            $usuario->setEAdministrador(0);
        }
        if ($usuario->alterarUsuario()) {
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, 'dadosUsuario.twig', [
                'action' => '/usuario/' . $id,
                'corpoMensagem' => 'Usuário alterado com sucesso',
                'erro' => 'mensagem',
                'usuario' => $usuario,
                'editar' => true,
                'alterarSenha' => true,
                'numCol' => '6',
                'edicao' => true,
            ]);
        } else {
            $response->withHeader('Location', '/usuario/' . $usuario->getCodUsuario())->write('Erro ao alterar');
            return $response;
        }
    }

    public function editar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $view = \Slim\Views\Twig::fromRequest($request);
        $usuario = new Usuario();
        $erro = '';
        $corpoMensagem = '';
        if (isset($_SESSION['erro']) && $_SESSION['erro'] === 'mensagem') {
            $erro = $_SESSION['erro'];
            $corpoMensagem = $_SESSION['corpoMensagem'];
            unset($_SESSION['erro']);
            unset($_SESSION['corpoMensagem']);
        }
        if ($usuario->localizarUsuario($id)) {
            if ($usuario->getCodUsuario() === $_SESSION['usuario']['codigo']) {
                $usuarioAtual = true;
            } else {
                $usuarioAtual = false;
            }
            return $view->render($response, 'dadosUsuario.twig', [
                'action' => '/usuario/' . $id,
                'usuario' => $usuario,
                'editar' => true,
                'alterarSenha' => true,
                'numCol' => '6',
                'edicao' => true,
                'usuarioAtual' => $usuarioAtual,
                'erro' => $erro,
                'corpoMensagem' => $corpoMensagem,
            ]);
        }
        return $response->withHeader('Location', '/usuario')->withStatus(404);
    }

    public function formInserir(Request $request, Response $response, $args)
    {
        unset($_SESSION['aba']);
        $erro = '';
        $corpoMensagem = '';
        if (isset($_SESSION['erro']) && $_SESSION['erro'] === 'mensagem') {
            $erro = $_SESSION['erro'];
            $corpoMensagem = $_SESSION['corpoMensagem'];
            unset($_SESSION['erro']);
            unset($_SESSION['corpoMensagem']);
        }
        if(!isset($_SESSION['usuario']['administrador']) || $_SESSION['usuario']['administrador'] === 0){
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Você não tem permissão para inserir usuários!';
            return $response->withHeader('Location', '/usuario')->withStatus(302);
        }
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'dadosUsuario.twig', [
            'action' => '/usuario/inserir',
            'inserir' => true,
            'alterarSenha' => false,
            'erro' => $erro,
            'corpoMensagem' => $corpoMensagem,
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        $post = $request->getParsedBody();
        var_dump($post);
        $usuario = new Usuario();
        $usuario->setCpf($post['cpf']);
        $usuario->setNomeDeUsuario(limpaMascara($post['cpf']));
        $usuario->setNome($post['nome']);
        if (isset($post['senha']) && $post['senha'] !== '' &&
            isset($post['confirmacao']) && $post['confirmacao'] !== '') {
            echo '<br/>Senha: ' . $post['senha'];
            $usuario->setSenha($post['senha']);
        } else {
            $response->getBody()->write('As senhas digitadas não conferem!');
        }
        $usuario->setEmail($post['email']);
        if (isset($post['eAdministrador'])) {
            $usuario->setEAdministrador(1);
        } else {
            $usuario->setEAdministrador(0);
        }
        if ($post['id'] === '') {
            if ($usuario->inserirUsuario()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Usuário inserido com sucesso!';
                return $response->withHeader('Location', '/usuario/' . $usuario->getCodUsuario())->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível inserir o usuário! Verifique as informações e tente novamente.';
                return $response->withHeader('Location', '/usuario/inserir')->withStatus(302);
//                $view = \Slim\Views\Twig::fromRequest($request);
//                return $view->render($response, 'dadosUsuario.twig', [
//                    'action' => '/usuario/inserir',
//                    'corpoMensagem' => 'Não foi possível inserir o usuário! Verifique as informações e tente novamente.',
//                    'erro' => 'mensagem',
//                    'post' => $post,
//                    'senha' => true,
//                ]);
            }
        }
    }

    public function alterarSenha(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $post = $request->getParsedBody();
        var_dump($post); echo '<br/>';
        $usuario = new Usuario();
        if(!isset($_SESSION['usuario']['administrador']) || $_SESSION['usuario']['administrador'] === 0){
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Você não tem permissão para alterar a senha de outro usuário!';
            return $response->withHeader('Location', '/usuario')->withStatus(302);
        }
        if ($id === $post['usuario']) {
            if ($usuario->localizarUsuario($id)) {
                if (isset($post['password']) && $post['password'] !== '') {
                    if (!password_verify($post['password'], $usuario->getSenha())) {
                        $_SESSION['erro'] = 'mensagem';
                        $_SESSION['corpoMensagem'] = 'A senha atual não confere!';
                        return $response->withHeader('Location', '/usuario/' . $id)->withStatus(302);
                    } else {
                        $_SESSION['erro'] = 'mensagem';
                        $_SESSION['corpoMensagem'] = 'A senha atual não confere!';
                        return $response->withHeader('Location', '/usuario/' . $id)->withStatus(302);
                    }
                }
                if (isset($post['senha']) && $post['senha'] !== ''
                    && isset($post['confirma']) && $post['confirma'] !== '' && $post['senha'] === $post['confirma']) {
                    $usuario->setSenha($post['senha']);
                    if ($usuario->alterarSenha()) {
                        $_SESSION['erro'] = 'mensagem';
                        $_SESSION['corpoMensagem'] = 'Senha alterada com sucesso!';
                        return $response->withHeader('Location', '/usuario/' . $id)->withStatus(302);
                    } else {
                        $_SESSION['erro'] = 'mensagem';
                        $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao processar sua solicitação! Verifique as informações e tente novamente.';
                        return $response->withHeader('Location', '/usuario/' . $id)->withStatus(302);
                    }
                } else {
                    $_SESSION['erro'] = 'mensagem';
                    $_SESSION['corpoMensagem'] = 'As senhas digitadas não conferem!';
                    return $response->withHeader('Location', '/usuario/' . $id)->withStatus(302);
                }
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao processar sua solicitação! Verifique as informações e tente novamente.';
                return $response->withHeader('Location', '/usuario/' . $id)->withStatus(302);
            }

        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao processar sua solicitação! Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/usuario/' . $id)->withStatus(302);
        }
    }
}