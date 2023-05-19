<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\Chapa;
use src\model\ConsultaEleitoral as Consulta;
use src\model\Eleitor;
use src\model\Proporcao;
use src\model\Urna;
use src\model\Vinculo;


class relatorioController
{
    public function relatorioZerezimo(Request $request, Response $response, $args)
    {
        //TODO - criar logica para gerar o relatorio
        $id = $args['id'];
        $consulta = new Consulta();
        if($consulta->localizarConsultaEleitoral($id)){
            $dataZerezimo = false;
            if($consulta->getDataZerezimo() != null){
                $dataZerezimo = date('d/m/Y H:i', strtotime($consulta->getDataZerezimo()));
            }
            $urna = new Urna();
            $urnas = $urna->listarUrnas($consulta->getCodConsultaEleitoral());
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, 'relatorioZerezimo.twig', [
                'raiz' => $request->getUri()->getHost(),
                'consulta' => $consulta,
                'dataZerezimo' => $dataZerezimo,
                'urnas' => $urnas
            ]);
        }
    }

    public function totalizarVotos(Request $request, Response $response, $args)
    {
        //TODO - criar logica para gerar o relatorio
        $id = $args['id'];
        $consulta = new Consulta();
        if($consulta->localizarConsultaEleitoral($id)){
            if(!$consulta->votacaoEncerrada()){
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'A votação ainda está em andamento! Aguardar o encerramento da votação para totalizar os votos.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            // TODO - verificar se não houve tentativa de fraude
            $chapa = new Chapa();
            $totalizarVotos =  array();
            $chapas = $chapa->listarChapas($consulta->getCodConsultaEleitoral());
            foreach ($chapas as $listaChapas) {
                $totalizarVotos[$listaChapas['codChapa']] = 0;
            }
            $votosTotais = $consulta->contarVotosTotais();
            if($votosTotais <=0) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não houve votos para serem totalizados!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $votantes = $consulta->contarVotantes();
            $totalAusentes = $votantes - $votosTotais;
            $eleitor = new Eleitor();
            $totalValidos = $eleitor->contarVotantes($consulta->getCodConsultaEleitoral());
            $votosTotaisAjustados = array();
            $vinculo = new Vinculo();
            $vinculos = $vinculo->listarVinculos();
            $proporcao = new Proporcao();
            $totalizacao = $consulta->totalizarVotosTotais();
            foreach ($chapas as $value){
                foreach ($vinculos as $item) {
                    $votosTotaisAjustados[$value['codChapa']][$item['codVinculo']] = 0;
                    $conta = $consulta->totalizarVotosValidosPorChapaPorVinculo($value['codChapa'], $item['codVinculo']);
                    $proporcao->buscarProporcaoPorConsultaEleitoralPorVinculo($consulta->getCodConsultaEleitoral(), $item['codVinculo']);
                    $votantesVinculo = $eleitor->totalVotantePorVinculo($consulta->getCodConsultaEleitoral(), $item['codVinculo']);
                    $contas = $proporcao->getPeso() * ($conta['conta'] / $votantesVinculo);
//                    $contas = round($contas, 2);
                    $votosTotaisAjustados[$value['codChapa']][$item['codVinculo']] += $contas;
                }
            }
            $maximo = 0;
            $chaveMaximo = 0;
            foreach ($votosTotaisAjustados as $key => $value){
                $soma = array_sum($value);
                if($soma > $maximo){
                    $maximo = $soma;
                    $chaveMaximo = $key;
                }
            }
            $chapa->buscarChapa($chaveMaximo);
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, 'totalizar.twig', [
                'raiz' => $request->getUri()->getHost(),
                'consulta' => $consulta,
                'totalVotantes' => $votantes,
                'totalAusentes' => $totalAusentes,
                'totalValidos' => $totalValidos,
                'totalizacao' => $totalizacao,
                'chapa' => $chapa->getDescricao(),
                'totalVotos' => round($maximo,2)
            ]);
        }
    }
}