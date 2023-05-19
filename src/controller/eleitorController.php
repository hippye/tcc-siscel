<?php

namespace src\controller;

use DI\ContainerBuilder;
use League\Csv\Reader;
use League\Csv\Writer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use src\model\Eleitor;
use src\model\Urna;
use src\model\Vinculo;
use function PHPUnit\Framework\directoryExists;
use function PHPUnit\Framework\isEmpty;

class eleitorController
{
    public function mostrar(Request $request, Response $response, $args)
    {
        $view = \Slim\Views\Twig::fromRequest($request);
        $_SESSION['aba'] = 'eleitores';
        $id = $args['id'];
        $idEleitor = $args['ide'];
        $eleitor = new Eleitor();
        $vinculo = new Vinculo();
        $urna = new Urna();
        if ($eleitor->buscarEleitor($idEleitor)) {
            return $view->render($response, 'eleitor.twig', [
                'eleitor' => $eleitor,
                'consulta' => $args['id'],
                'vinculos' => $vinculo->listarVinculos(),
                'urnas' => $urna->listarUrnas($id),
            ]);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Ocorreu um erro ao processar sua solicitação. Verifique os dados e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function inserir(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $post = $request->getParsedBody();
        $eleitor = new Eleitor();
        if ($eleitor->buscarEleitorPorCpfPorConsultaEleitoral($post['cpf'], $id)) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Já existe um eleitor cadastrado com esse CPF.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $eleitor->setCodConsultaEleitoral($id);
        $eleitor->setCpf($post['cpf']);
        $eleitor->setNome($post['nome']);
        $eleitor->setEmail($post['email']);
        $eleitor->setCodVinculo($post['vinculo']);
        $eleitor->setCodUrna($post['urna']);
        $eleitor->setAceitaTermo(1);
//        $eleitor->setComprovanteDeVoto(null);
        $eleitor->setIdVotacao(gerarString(12, 0, 1, 0, 0, 1));
        $eleitor->setSenhaVotacao(gerarString(16, 1, 1, 1, 1, 1));
        $_SESSION['aba'] = 'eleitores';
        if ($eleitor->inserirEleitor()) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Eleitor cadastrado com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível cadastrar o eleitor. Verifique os dados e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function alterar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idEleitor = $args['ide'];
        $post = $request->getParsedBody();
        $eleitor = new Eleitor();
        $eleitor->buscarEleitor($idEleitor);
        if ($post['cpf'] != $eleitor->getCpf()) {
            if ($eleitor->buscarEleitorPorCpfPorConsultaEleitoral($post['cpf'], $id)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Já existe um eleitor cadastrado com esse CPF. Verifique os dados e tente novamente.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        }
        $eleitor->setCpf($post['cpf']);
        $eleitor->setNome($post['nome']);
        $eleitor->setEmail($post['email']);
        $eleitor->setCodVinculo($post['vinculo']);
        $eleitor->setCodUrna($post['urna']);
        $_SESSION['aba'] = 'eleitores';
        if ($eleitor->alterarEleitor()) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Eleitor alterado com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível alterar o eleitor. Verifique os dados e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function excluir(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idEleitor = $args['ide'];
        $eleitor = new Eleitor();
        $eleitor->buscarEleitor($idEleitor);
        $_SESSION['aba'] = 'eleitores';
        if($eleitor->getComprovanteDeVoto() != null){
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível excluir o eleitor. Ele já votou.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        if ($eleitor->excluirEleitor()) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Eleitor excluído com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível excluir o eleitor. Verifique os dados e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function inserirCsv(Request $request, Response $response, $args)
    {
        $_SESSION['aba'] = 'eleitores';
        $id = $args['id'];
        $post = $request->getParsedBody();
        $arquivo = $request->getUploadedFiles()['arquivo'];
        $tipoArquivo = $arquivo->getClientMediaType();
        $nomeArquivo = pathinfo($arquivo->getClientFilename(), PATHINFO_FILENAME);
        if ($post['urna'] == '') {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Selecione uma urna para vincular os eleitores.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        if (!in_array($tipoArquivo, TIPOS_PERMITIDOS, true)) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Tipo de arquivo não permitido. Envie um arquivo no formato .csv';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $view = \Slim\Views\Twig::fromRequest($request);
        $eleitor = new Eleitor();
        $erros = array();
        if ($arquivo->getError() === UPLOAD_ERR_OK) {
            echo 'arquivo ok';
            $csv = array_map('str_getcsv', file($arquivo->getStream()->getMetadata('uri')));
            foreach ($csv as $linha) {
                if ($eleitor->buscarEleitorPorCpfPorConsultaEleitoral($linha[0], $id)) {
                    $erros[] = [
                        'cpf' => $linha[0],
                        'nome' => $linha[1],
                        'email' => $linha[2],
                        'vinculo' => $linha[3],
                        'erro' => 'Eleitor já cdastrado'
                    ];
                    continue;
                }
                $eleitor->setCodConsultaEleitoral($id);
                $eleitor->setCpf($linha[0]);
                $eleitor->setNome($linha[1]);
                $eleitor->setEmail($linha[2]);
                $eleitor->setCodVinculo($linha[3]);
                $eleitor->setCodUrna($post['urna']);
                $eleitor->setAceitaTermo(1);
                $eleitor->setIdVotacao(gerarString(12, 0, 1, 0, 0, 1));
                $eleitor->setSenhaVotacao(gerarString(16, 1, 1, 1, 1, 1));
                if (!$eleitor->inserirEleitor()) {
                    $erros[] = [
                        'cpf' => $linha[0],
                        'nome' => $linha[1],
                        'email' => $linha[2],
                        'vinculo' => $linha[3],
                        'erro' => 'Não foi possível inserir o eleitor'
                    ];
                }
            }
            if ($erros) {
                if (!directoryExists(__DIR__ . '/../../public/eleicoes/' . $id)) {
                    if (!mkdir($concurrentDirectory = __DIR__ . '/../../public/uploads/eleicoes/' . $id, 0777, true) && !is_dir($concurrentDirectory)) {
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                    }
                }
                $erro = fopen(__DIR__ . '/../../public/uploads/eleicoes/' . $id . '/' . date('Y-m-d-H-i') . '-' . $nomeArquivo . '-erros-eleitores.csv', 'w');
                foreach ($erros as $linha) {
                    $linha = array_values($linha);
                    fputcsv($erro, $linha);
                }
                fclose($erro);
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível inserir todas os eleitores. Verifique o arquivo de erros.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);

            } else {
                echo 'sucesso';
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Eleitores inseridos com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível inserir os eleitores. Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }
}