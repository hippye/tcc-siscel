<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\Proporcao;
use src\model\Vinculo;

class proporcaoController
{
    public function mostrar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idProporcao = $args['idp'];
        $proporcao = new Proporcao();
        $vinculo = new Vinculo();
        if ($proporcao->buscarProporcao($idProporcao)) {
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, 'proporcao.twig', [
                'raiz' => $request->getUri()->getHost(),
                'proporcao' => $proporcao,
                'vinculos' => $vinculo->listarVinculos(),
                'consulta' => $id,
            ]);
        }
        $_SESSION['erro'] = 'mensagem';
        $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao exibir a proporção. Verifique as informações e tente novamente.';
        return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);

    }

    public function inserir(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $post = $request->getParsedBody();
        $proporcao = new Proporcao();
        $_SESSION['aba'] = 'proporcao';
        $soma = $proporcao->contarProporcoes($id);
        if ($proporcao->buscarProporcaoPorConsultaEleitoralPorVinculo($id, $post['vinculo'])) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Já existe uma proporção cadastrada para o vinculo selecionado. Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        if($soma + $post['peso'] > 100){
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possivel inserir a proporção. A soma das proporções não pode ser maior que 100. Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $proporcao->setCodVinculo($post['vinculo']);
        $proporcao->setCodConsultaEleitoral($id);
        $proporcao->setPeso($post['peso']);
        if ($proporcao->inserirProporcao()) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Proporção inserida com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $_SESSION['erro'] = 'mensagem';
        $_SESSION['corpoMensagem'] = 'Não foi possível inserir a proporção. Verifique as informações e tente novamente.';
        return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
    }

    public function alterar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idProporcao = $args['idp'];
        $post = $request->getParsedBody();
        $proporcao = new Proporcao();
        $_SESSION['aba'] = 'proporcao';
        if ($proporcao->buscarProporcao($idProporcao)) {
            if ($proporcao->getCodVinculo() != $post['vinculo']) {
                if ($proporcao->buscarProporcaoPorConsultaEleitoralPorVinculo($id, $post['vinculo'])) {
                    $_SESSION['erro'] = 'mensagem';
                    $_SESSION['corpoMensagem'] = 'Já existe uma proporção cadastrada para o vinculo selecionado. Verifique as informações e tente novamente.';
                    return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
                }
            }
            $soma = $proporcao->contarProporcoes($id);
            if($soma - $proporcao->getPeso() + $post['peso'] > 100){
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível alterar a proporção. A soma das proporções não pode ser maior que 100. Verifique as informações e tente novamente.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $proporcao->setCodVinculo($post['vinculo']);
            $proporcao->setPeso($post['peso']);
            if ($proporcao->alterarProporcao()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Proporção alterada com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível alterar a proporção. Verifique as informações e tente novamente.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        }
        $_SESSION['erro'] = 'mensagem';
        $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao alterar a proporção. Verifique as informações e tente novamente.';
        return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
    }

    public function excluir(Request $request, Response $response, $args){
        $id = $args['id'];
        $idProporcao = $args['idp'];
        $proporcao = new Proporcao();
        $_SESSION['aba'] = 'proporcao';
        if ($proporcao->buscarProporcao($idProporcao)) {
            if ($proporcao->excluirProporcao()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Proporção excluída com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível excluir a proporção. Verifique as informações e tente novamente.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        }
        $_SESSION['erro'] = 'mensagem';
        $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao excluir a proporção. Verifique as informações e tente novamente.';
        return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
    }
}