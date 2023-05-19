<?php

namespace src\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SplFileObject;
use src\model\ConsultaEleitoral;
use src\model\Urna;
use function PHPUnit\Framework\isEmpty;

class urnaController
{
    public function mostrar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idUrna = $args['idu'];
        $urna = new Urna();
        $_SESSION['aba'] = 'urnas';
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'urna.twig', [
            'raiz' => $request->getUri()->getHost(),
            'id' => $id,
            'urna' => $urna->buscarUrna($idUrna),
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $urna = new Urna();
        $post = $request->getParsedBody();
        $urna->setDescricao($post['descricao']);
        $urna->setCodConsultaEleitoral($id);
        $_SESSION['aba'] = 'urnas';
        if ($urna->inserirUrna()) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Urna cadastrada com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $_SESSION['erro'] = 'mensagem';
        $_SESSION['corpoMensagem'] = 'Não foi possível inserir a urna. Verifique as informações e tente novamente.';
        return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
    }

    public function alterar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $idUrna = $args['idu'];
        $post = $request->getParsedBody();
        $urna = new Urna();
        $_SESSION['aba'] = 'urnas';
        if ($urna->buscarUrna($idUrna)) {
            $urna->setDescricao($post['descricao']);
            $urna->setCodConsultaEleitoral($id);
            if ($urna->alterarUrna($idUrna)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Urna alterada com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível alterar a urna. Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $_SESSION['erro'] = 'mensagem';
        $_SESSION['corpoMensagem'] = 'ocorreu um erro ao alterar a urna. Verifique as informações e tente novamente.';
        return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
    }

    public function excluir(Request $request, Response $response, $args)
    {
        $_SESSION['aba'] = 'urnas';
        $id = $args['id'];
        $idUrna = $args['idu'];
        $urna = new Urna();
        $consulta = new ConsultaEleitoral();
        $consulta->localizarConsultaEleitoral($id);
        if($consulta->votacaoAberta() || $consulta->getDataZerezimo()){
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não é possível excluir urnas de uma consulta que já iniciou a votação ou que já foi congelada.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        if ($urna->buscarUrna($idUrna)) {

            if ($urna->excluirUrna($idUrna)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Urna excluída com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível excluir a urna. Verifique as informações e tente novamente.';
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível excluir a urna. Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }

    public function inserirCsv(Request $request, Response $response, $args)
    {
        $_SESSION['aba'] = 'urnas';
        $id = $args['id'];
        $arquivos = $request->getUploadedFiles();
        $arquivo = $arquivos['arquivo'];
        $tipoArquivo = $arquivo->getClientMediaType();
        $nomeArquivo = pathinfo($arquivo->getClientFilename(), PATHINFO_FILENAME);
        if(!in_array($tipoArquivo, TIPOS_PERMITIDOS, true)){
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Tipo de arquivo não permitido. Envie um arquivo no formato .csv';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
        $view = \Slim\Views\Twig::fromRequest($request);
        $urna = new Urna();
        if($arquivo->getError() === UPLOAD_ERR_OK){
            $csv = array_map('str_getcsv', file($arquivo->getStream()->getMetadata('uri')));
            foreach ($csv as $linha) {
                if($urna->buscarUrnaPorDescricao($linha[0], $id)){
                    $erros[] = [
                        'descricao' => $linha[0],
                        'erro' => 'Urna já cadastrada'
                    ];
                    continue;
                }
                $urna->setDescricao($linha[0]);
                $urna->setCodConsultaEleitoral($id);
                if(!$urna->inserirUrna()){
                    $erros[] = [
                        'descricao' => $linha[0],
                        'erro' => 'Não foi possível inserir a urna'
                    ];
                }
            }
            if($erros){
                if (!file_exists(__DIR__ . '/../../public/eleicoes/' . $id)) {
                    echo 'nao existe';
                    if (!mkdir($concurrentDirectory = __DIR__ . '/../../public/uploads/eleicoes/' . $id, 0777, true) && !is_dir($concurrentDirectory)) {
                        echo 'nao criou';
                        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                    }
                }
                $erro = fopen(__DIR__ . '/../../public/uploads/eleicoes/' . $id . '/' . date('Y-m-d-H-i') . '-' . $nomeArquivo .'-erros-urnas.csv', 'w');
                foreach ($erros as $linha) {
                    $linha = array_values($linha);
                    fputcsv($erro, $linha);
                }
                fclose($erro);
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível inserir todas as urnas. Verifique o arquivo de erros.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);

            } else {
                echo 'sucesso';
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Urnas inseridas com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível inserir as urnas. Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
        }
    }
}