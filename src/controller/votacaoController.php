<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\Chapa;
use src\model\ConsultaEleitoral;
use src\model\Eleitor;
use src\model\Voto;

class votacaoController
{
    public function mostrar(Request $request, Response $response, $args)
    {
        if(isset($_SESSION['voto'])) {
            unset($_SESSION['voto']);
        }
        $id = $args['id'];
        $consulta = new ConsultaEleitoral();
        if(!$consulta->localizarConsultaEleitoralPorVotacao($id)) {
            return $response->withHeader('Location', '/votacao/erro');
        }
        $eleitor = new Eleitor();
        $eleitor->buscarEleitorPorIdVotacao($_SESSION['idVotacao']['id']);
        if($eleitor->getComprovanteDeVoto() != null && $eleitor->getComprovanteDeVoto() != '') {
            $_SESSION['mensagem'] = 'Você já votou!';
            return $response->withHeader('Location', '/votacao/erro')->withStatus(302);
        }
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'votacao/inicio.twig', [
            'erro' => '',
            'mensagem' => '',
            'consulta' => $consulta,
            'idVotacao' => $id,
            'idEleitor' => $_SESSION['idVotacao']['id'],
        ]);
    }
    public function mostrarCedula(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $view = \Slim\Views\Twig::fromRequest($request);
        $consulta = new ConsultaEleitoral();
        if($consulta->localizarConsultaEleitoralPorVotacao($id)) {
            $chapa = new Chapa();
            $chapas = $chapa->listarChapas($consulta->getCodConsultaEleitoral());
        }
        return $view->render($response, 'votacao/votacao.twig', [
            'idvotacao' => $id,
            'chapas' => $chapas,
            'idVotacao' => $id,
            'urna' => $_SESSION['idVotacao']['urna'],
            'vinculo' => $_SESSION['idVotacao']['vinculo'],
        ]);
    }

    public function revisar(Request $request, Response $response, $args){
        $id = $args['id'];
        $view = \Slim\Views\Twig::fromRequest($request);
        $post = $request->getParsedBody();
        $consulta = new ConsultaEleitoral();
        if($consulta->localizarConsultaEleitoralPorVotacao($id)) {
            $voto = new Voto();
            $voto->setCodConsultaEleitoral($consulta->getCodConsultaEleitoral());
            $voto->setCodUrna($post['urna']);
            $voto->setCodVinculo($post['vinculo']);
            switch ($post['chapa']) {
                case 'branco':
                    $chapa = 'Voto em Branco';
                    $voto->setVotoBranco(1);
                    break;
                case 'nulo':
                    $chapa = 'Voto Nulo';
                    $voto->setVotoNulo(1);
                    break;
                default:
                    $chapa = new Chapa();
                    $chapa->buscarChapa($post['chapa']);
                    $chapa = $chapa->getDescricao();
                    $voto->setCodChapa($post['chapa']);
                    break;
            }
            if(isset($_SESSION['voto'])) {
                unset($_SESSION['voto']);
                return $response->withHeader('Location', '/votacao/' . $id)->withStatus(302);
            }
            $_SESSION['voto'] = $post;
            return $view->render($response, 'votacao/revisar.twig', [
                'idvotacao' => $id,
                'voto' => $voto,
                'idVotacao' => $id,
                'chapa' => $chapa,
                'urna' => $_SESSION['idVotacao']['urna'],
                'vinculo' => $_SESSION['idVotacao']['vinculo'],
            ]);
        } else {
            $_SESSION['mensagem'] = 'Ocorreu um erro ao tentar processar sua solicitação.';
            return $response->withHeader('Location', '/votacao/erro');
        }
    }

    public function depositar(Request $request, Response $response, $args) {
        $id = $args['id'];
        if(!isset($_SESSION['voto'])) {
            return $response->withHeader('Location', '/votacao/' . $id)->withStatus(302);
        }
        $votacao = $_SESSION['voto'];
        var_dump($votacao);
        $consulta = new ConsultaEleitoral();
        if($consulta->localizarConsultaEleitoralPorVotacao($id) && $id === $votacao['idVotacao']) {
            echo 'entrou';
            $voto = new Voto();
            $voto->setCodConsultaEleitoral($consulta->getCodConsultaEleitoral());
            $voto->setCodUrna($votacao['urna']);
            $voto->setCodVinculo($votacao['vinculo']);
            switch ($votacao['chapa']) {
                case 'branco':
                    $chapa = 'Voto em Branco';
                    $voto->setVotoBranco(1);
                    break;
                case 'nulo':
                    $chapa = 'Voto Nulo';
                    $voto->setVotoNulo(1);
                    break;
                default:
                    $chapa = new Chapa();
                    $chapa->buscarChapa($votacao['chapa']);
                    $chapa = $chapa->getDescricao();
                    $voto->setCodChapa($votacao['chapa']);
                    break;
            }
            if($voto->inserirVoto()) {
                $eleitor = new Eleitor();
                if($eleitor->buscarEleitorPorIdVotacao($_SESSION['idVotacao']['id'])) {
                    $eleitor->setComprovanteDeVoto(hash('haval160,4', time() . ' ' . $eleitor->getCodEleitor()));
                    if($eleitor->alterarEleitor()) {
                        session_destroy();
                        session_start();
                        $_SESSION['comprovante']['comprovante'] = $eleitor->getComprovanteDeVoto();
                        $_SESSION['comprovante']['eleitor'] = $eleitor->getCodEleitor();
                        return $response->withHeader('Location', '/votacao/comprovante')->withStatus(302);
                    } else {
                        $_SESSION['mensagem'] = 'Ocorreu um erro ao tentar processar sua solicitação.';
                        return $response->withHeader('Location', '/votacao/erro');
                    }
                } else {
                    $_SESSION['mensagem'] = 'Ocorreu um erro ao tentar processar sua solicitação.';
                    return $response->withHeader('Location', '/votacao/erro');
                }
            } else {
                $_SESSION['mensagem'] = 'Ocorreu um erro ao tentar processar seu voto.';
                return $response->withHeader('Location', '/votacao/erro');
            }
        } else {
            $_SESSION['mensagem'] = 'Ocorreu um erro ao tentar processar sua solicitação.';
            return $response->withHeader('Location', '/votacao/erro');
        }
    }

    public function comprovante(Request $request, Response $response){
        if(isset($_SESSION['comprovante'])) {
            $eleitor = new Eleitor();
            $eleitor->buscarEleitor($_SESSION['comprovante']['eleitor']);
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, 'votacao/comprovante.twig', [
                'comprovante' => $_SESSION['comprovante']['comprovante'],
                'eleitor' => $eleitor,
            ]);
        } else {
            return $response->withHeader('Location', '/votacao')->withStatus(302);
        }
    }

    public function login(Request $request, Response $response, $args)
    {
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'votacao/login.twig', [
            'erro' => '',
            'mensagem' => '',
        ]);
    }

    public function logar(Request $request, Response $response, $args)
    {
        $dados = $request->getParsedBody();
        $usuario = new Eleitor();
        if($usuario->buscarEleitorPorIdVotacao($dados['username'])) {
            if ($dados['password'] === $usuario->getSenhaVotacao()) {
                echo 'logado';
                $_SESSION['idVotacao']['logado'] = true;
                $_SESSION['idVotacao']['id'] = $usuario->getIdVotacao();
                $_SESSION['idVotacao']['token'] = $usuario->atualizarToken(session_id(), time());
                $_SESSION['idVotacao']['ultimoAcesso'] = time();
                $_SESSION['idVotacao']['urna'] = $usuario->getCodUrna();
                $_SESSION['idVotacao']['vinculo'] = $usuario->getCodVinculo();
                return $response->withHeader('Location', $_SESSION['redirecionar'])->withStatus(302);
            }
            exit();
            $_SESSION['mensagem'] = 'Usuário ou senha inválidos';
            return $response->withHeader('Location', '/votacao/erro')->withStatus(302);
        } else {
            $_SESSION['mensagem'] = 'Usuário ou senha inválidos';
            return $response->withHeader('Location', '/votacao/erro')->withStatus(302);
        }
    }

    public function logout(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        session_destroy();
        return $response->withHeader('Location', '/votacao/' . $id)->withStatus(302);
    }

    public function erro(Request $request, Response $response, $args)
    {
        $mensagem = $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'votacao/erro.twig', [
            'mensagem' => $mensagem,
        ]);
    }
}