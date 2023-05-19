<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\cargoComissao;
use src\model\MembroComissao;
use src\model\MembroComissao as Membro;
use src\model\ConsultaEleitoral as Consulta;
use src\model\Usuarios;

class membroController
{
    public function insert(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $_SESSION['aba'] = 'comissao';
        $consulta = new Consulta();
        $consulta->localizarConsultaEleitoral($id);
        $post = $request->getParsedBody();
        if ($id === $post['consulta']) {
            $membro = new Membro();
            if ($membro->buscarMembroComissaoPorCodUsuarioPorConsultaEleitoral($post['pessoa'], $id)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'O usuário já é membro da comissão! Verifique a informação e tente novamente!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $membro->setCodConsultaEleitoral($id);
            $membro->setCodUsuario($post['pessoa']);
            $membro->setCodCargoComissao($post['cargo']);
            if ($membro->inserirMembroComissao()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Membro da comissão inserido com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Erro ao inserir o membro da comissão! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function excluir(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $_SESSION['aba'] = 'comissao';
        $idMembro = $args['idm'];
        $membro = new Membro();
        $membros = $membro->listarMembrosComissao($id);
        if ($membro->buscarMembroComissao($idMembro)) {
            if ($membro->excluirMembroComissao()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Membro da comissão excluído com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível excluir o membro da comissão! Verifique as informações e tente novamente!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível excluir o membro da comissão! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function mostrar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idMembro = $args['idm'];
        $membro = new Membro();
        $usuarios = new Usuarios();
        $membro->buscarMembroComissao($idMembro);
        $cargo = new cargoComissao();
        $_SESSION['aba'] = 'comissao';
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, "membroComissao.twig", [
            'usuarios' => $usuarios->listarUsuarios(),
            'membro' => $membro,
            'idMembro' => $membro->getCodUsuario(),
            'consulta' => $id,
            'cargos' => $cargo->listarCargoComissao()
        ]);
    }

    public function alterar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idMembro = $args['idm'];
        $post = $request->getParsedBody();
        $membro = new MembroComissao();
        $cargo = new cargoComissao();
        $_SESSION['aba'] = 'comissao';
        if ($membro->buscarMembroComissao($idMembro)) {
            if ($membro->getCodUsuario() != $post['pessoa']) {
                if ($membro->buscarMembroComissaoPorCodUsuarioPorConsultaEleitoral($post['pessoa'], $id)) {
                    $_SESSION['erro'] = 'mensagem';
                    $_SESSION['corpoMensagem'] = 'O usuário já é membro da comissão! Verifique a informação e tente novamente!';
                    return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
                }
            }
            if ($membro->buscarCargoEmUso($id)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'O cargo já está em uso! Verifique a informação e tente novamente!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $membro->setCodCargoComissao($post['cargo']);
            $membro->setCodUsuario($post['pessoa']);
            if ($membro->alterarMembroComissao()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Membro da comissão alterado com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível alterar o membro da comissão! Verifique as informações e tente novamente!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao tentar alterar o membro da comissão! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }
}