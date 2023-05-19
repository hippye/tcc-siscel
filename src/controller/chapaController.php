<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\Chapa;
use src\model\ConsultaEleitoral;

class chapaController
{
    public function insert(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $post = $request->getParsedBody();
        if ($id !== $post['consulta']) {
            $_SESSION['aba'] = 'chapas';
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Erro ao inserir a chapa! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $chapa = new Chapa();
        $chapa->setDescricao($post['descricao']);
        $_SESSION['aba'] = 'chapas';
        if ($chapa->buscarChapaPorNumero($id, $post['numeroChapa'])) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Já existe uma chapa com esse número! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $chapa->setNumeroChapa($post['numeroChapa']);
        $chapa->setCodConsultaEleitoral($id);
        if ($chapa->inserirChapa()) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Chapa inserida com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao tentar inserir a chapa! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function excluir(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idChapa = $args['idc'];
        $chapa = new Chapa();
        $_SESSION['aba'] = 'chapas';
        $consulta = new ConsultaEleitoral();
        if($consulta->localizarConsultaEleitoral($id)){
            if($consulta->getDataZerezimo() !== null){
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não é possível excluir chapas de uma consulta que já foi congelada!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Houve um erro ao tentar excluir a chapa! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        if ($chapa->buscarChapa($idChapa)) {
            if ($chapa->excluirChapa()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Chapa excluída com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível excluir a chapa! Verifique as informações e tente novamente!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Houve um erro ao tentar excluir a chapa! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function mostrar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idChapa = $args['idc'];
        $chapa = new Chapa();
        $_SESSION['aba'] = 'chapas';
        if ($chapa->buscarChapa($idChapa)) {
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, 'chapa.twig', [
                'consulta' => $chapa->getCodConsultaEleitoral(),
                'chapa' => $chapa
            ]);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Houve um erro ao tentar mostrar a chapa! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function alterar(Request $request, Response $response, $args)
    {
        $_SESSION['aba'] = 'chapas';
        $id = $args['id'];
        $idChapa = $args['idc'];
        $post = $request->getParsedBody();
        $chapa = new Chapa();
        if ($chapa->buscarChapa($idChapa)) {
            $chapa->setDescricao($post['descricao']);
            if ($chapa->alterarChapa()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Chapa alterada com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível alterar a chapa! Verifique as informações e tente novamente!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Houve um erro ao tentar alterar a chapa! Verifique as informações e tente novamente!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }
}