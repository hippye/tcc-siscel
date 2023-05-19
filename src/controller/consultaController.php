<?php

namespace src\controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\model\Chapa;
use src\model\ConsultaEleitoral as Consulta;
use src\model\Eleitor;
use src\model\MembroComissao as Comissao;
use src\model\Proporcao;
use src\model\Urna;
use src\model\Usuarios;
use src\model\CargoComissao;
use src\model\Vinculo;
use src\model\Voto;

class consultaController
{
    public function listar(Request $request, Response $response, $args)
    {
        $usuario = $_SESSION['usuario']['codigo'];
        unset($_SESSION['aba']);
        $consulta = new Consulta();
        $view = \Slim\Views\Twig::fromRequest($request);
        if (!isset($_SESSION['usuario']['administrador']) || $_SESSION['usuario']['administrador'] != 1) {
            return $view->render($response, 'listarConsultas.twig', [
                'raiz' => $request->getUri()->getHost(),
                'consultasEleitorais' => $consulta->listarConsultasEleitoraisPorUsuario($usuario),
            ]);
        } else {
            return $view->render($response, 'listarConsultas.twig', [
                'raiz' => $request->getUri()->getHost(),
                'consultasEleitorais' => $consulta->listarConsultasEleitoraisPorUsuarioComCargo($usuario),
            ]);
        }
    }

    public function mostrar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $administrador = false;
        if (isset($_SESSION['usuario']['administrador']) && $_SESSION['usuario']['administrador'] == true) {
            $administrador = true;
        }
        $consulta = new Consulta();
        if ($consulta->localizarConsultaEleitoral($id)) {
            if ($consulta->getDataZerezimo() != null) {
                $congelar = false;
                $enviarLink = $consulta->getDataZerezimo();
            } else {
                $congelar = true;
                $enviarLink = false;
            }
            $comissao = new Comissao();
            $membros = $comissao->listarMembrosComissaoPorExtenso($id);
            $presidente = 0;
            if ($comissao->buscarCargoEmUso($id)) {
                $presidente = 1;
            }
            $cargo = new CargoComissao();
            $usuarios = new Usuarios();
            $listausuarios = $usuarios->listarUsuarios();
            $chapa = new Chapa();
            $chapas = $chapa->listarChapas($id);
            $vinculos = new Vinculo();
            $proporcao = new Proporcao();
            $urna = new Urna();
            $eleitor = new Eleitor();
            $erro = '';
            $corpoMensagem = '';
            $negativo = false;
            $aba = @$_SESSION['aba'];
            $diretorio = __DIR__ . '/../../public/uploads/eleicoes/' . $id;
            $relatorios = @scandir($diretorio); // TODO listar todos os arquivos dentro da pasta /uploads/$id
            if (!$relatorios) {
                $negativo = true;
            } else {
                $relatorios = array_diff($relatorios, array('.', '..'));
            }
            unset($_SESSION['aba']);
            switch ($aba) {
                default:
                case 'informacoes':
                    $abas['informacoes'] = 'show active';
                    $abas['informacoesAba'] = 'active';
                    break;
                case 'relatorios':
                    $abas['relatorios'] = 'show active';
                    $abas['relatoriosAba'] = 'active';
                    break;
                case 'comissao':
                    $abas['comissao'] = 'show active';
                    $abas['comissaoAba'] = 'active';
                    break;
                case 'proporcao':
                    $abas['proporcao'] = 'show active';
                    $abas['proporcaoAba'] = 'active';
                    break;
                case 'urnas':
                    $abas['urnas'] = 'show active';
                    $abas['urnasAba'] = 'active';
                    break;
                case 'eleitores':
                    $abas['eleitores'] = 'show active';
                    $abas['eleitoresAba'] = 'active';
                    break;
                case 'auditoria':
                    $abas['auditoria'] = 'show active';
                    $abas['auditoriaAba'] = 'active';
                    break;
                case 'chapas':
                    $abas['chapas'] = 'show active';
                    $abas['chapasAba'] = 'active';
                    break;
            }
            if (isset($_SESSION['erro']) && $_SESSION['erro'] == 'mensagem') {
                $erro = $_SESSION['erro'];
                $corpoMensagem = $_SESSION['corpoMensagem'];
                unset($_SESSION['erro']);
                unset($_SESSION['corpoMensagem']);
            }
            $view = \Slim\Views\Twig::fromRequest($request);
            return $view->render($response, 'dadosConsulta.twig', [
                'raiz' => $request->getUri()->getHost(),
                'action' => '/consulta/' . $id,
                'consulta' => $consulta,
                'membros' => $membros,
                'usuarios' => $listausuarios,
                'administrador' => $administrador,
                'numCol' => '6',
                'erro' => $erro,
                'corpoMensagem' => $corpoMensagem,
                'abas' => $abas,
                'enviarLink' => $enviarLink,
                'chapas' => $chapas,
                'presidente' => $presidente,
                'cargos' => $cargo->listarCargoComissao(),
                'vinculos' => $vinculos->listarVinculos(),
                'proporcoes' => $proporcao->listarProporcaoPorCosultaEleitoral($id),
                'urnas' => $urna->listarUrnas($id),
                'eleitores' => $eleitor->listarEleitoresComVinculoComUrna($id),
                'relatorios' => $relatorios,
                'negativo' => $negativo,
                'congelar' => $congelar,
            ]);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Consulta eleitoral não encontrada! Vrifique a informação e tente novamente.';
            return $response->withHeader('Location', '/consulta')->withStatus(302);
        }
    }


    public function insert(Request $request, Response $response, $args)
    {
        $post = $request->getParsedBody();
        $consulta = new Consulta();
        $consulta->setDescricao($post['descricao']);
        if ($consulta->inserirConsultaEleitoral()) {
            $membro = new Comissao();
            $membro->setCodConsultaEleitoral($consulta->getCodConsultaEleitoral());
            $membro->setCodUsuario($post['presidente']);
            $membro->setCodCargoComissao(1);
            $membro->inserirMembroComissao();
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Consulta inserida com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $consulta->getCodConsultaEleitoral())->withStatus(302);
        } else {
            echo "Não inseriu";
            return $response->withHeader('Location', '/consulta')->withStatus(302); //TODO - Tratar erro
        }
    }

    public function inserir(Request $request, Response $response, $args)
    {
        unset($_SESSION['aba']);
        $usuarios = new Usuarios();
        $usuarios = $usuarios->listarUsuarios();
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'inserirConsulta.twig', [
            'raiz' => $request->getUri()->getHost(),
            'numCol' => '6',
            'usuarios' => $usuarios,
        ]);
    }

    public function salvar(Request $request, Response $response, $args): Response
    {
        $post = $request->getParsedBody();
        $id = $args['id'];
        $consulta = new Consulta();
        $consulta->localizarConsultaEleitoral($id);
        $consulta->setDescricao($post['descricao']);
        $consulta->setDataInicio($post['dataInicio']);
        $consulta->setDataEncerramento($post['dataEncerramento']);
        $consulta->setHoraInicio($post['horaInicio']);
        $consulta->setHoraEncerramento($post['horaEncerramento']);
        $consulta->setUsarLdap(isset($post['usarLdap']) ? 1 : 0);
        if ($consulta->alterarConsultaEleitoral()) {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Consulta alterada com sucesso!';
            return $response->withHeader('Location', '/consulta/' . $consulta->getCodConsultaEleitoral())->withStatus(302);
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível alterar a consulta! Verifique as informações e tente novamente.';
            return $response->withHeader('Location', '/consulta')->withStatus(302);
        }
    }

    public function excluir(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $consulta = new Consulta();
        if ($consulta->localizarConsultaEleitoral($id)) {
            if ($consulta->excluirConsultaEleitoral()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Consulta excluída com sucesso!';
                return $response->withHeader('Location', '/consulta')->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível excluir a consulta!';
                return $response->withHeader('Location', '/consulta')->withStatus(302); //TODO - Tratar erro
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível completar sua solicitação! Verifique os dados e tente novamente.';
            return $response->withHeader('Location', '/consulta')->withStatus(302);
        }
    }

    public function congelar(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $consulta = new Consulta();
        if ($consulta->localizarConsultaEleitoral($id)) {
            $chapas = new Chapa();
            if (!$chapas->listarChapas($id)) {
                echo 'não tem chapa';
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível congelar a consulta! A consulta não possui chapas.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $urnas = new Urna();
            if (!$urnas = $urnas->listarUrnas($id)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível congelar a consulta! A consulta não possui urnas.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $eleitores = new Eleitor();
            if (!$eleitores->listarEleitores($id)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível congelar a consulta! A consulta não possui eleitores.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $votos = new Voto();
            foreach ($urnas as $urna) {
                if ($votos->contarVotosUrna($urna['codUrna']) != 0) {
                    $_SESSION['erro'] = 'mensagem';
                    $_SESSION['corpoMensagem'] = 'Não foi possível congelar a consulta! A urna ' . $urna->getNome() . ' possui votos.';
                    return $response->withHeader('Location', '/consulta')->withStatus(302);
                }
            }
            if ($consulta->efetuarZerezimo()) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Consulta congelada com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível congelar a consulta! Verifique os dados e tente novamente.';
                return $response->withHeader('Location', '/consulta')->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível completar sua solicitação! Verifique os dados e tente novamente.';
            return $response->withHeader('Location', '/consulta')->withStatus(302);
        }
    }

    public function enviarLink(Request $request, Response $response, $args)
    {
        $id = $args['id'];
        $consulta = new Consulta();
        if ($consulta->localizarConsultaEleitoral($id)) {
            $eleitores = new Eleitor();
            if (!$eleitores = $eleitores->listarEleitores($id)) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível enviar os links! A consulta não possui eleitores.';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            }
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            // Configure as informações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->Username = EMAIL;
            $mail->Password = SENHA;
            $mail->setFrom(EMAIL, 'Sistema de Consulta Eleitoral');
            $mail->Subject = 'Link para votação da ' . $consulta->getDescricao();
            $sucesso = false;
            foreach ($eleitores as $eleitor) {
                $destinatário = $eleitor['email'];
                $mail->addAddress($eleitor['email'], $eleitor['nome']);
                $mensagem = "A eleição " . $consulta->getDescricao() . " está disponível para seu voto.<br><br>"
                        . "A votação irá iniciar às <strong>" . $consulta->getHoraInicio() . "</strong> do dia <strong>" . date('d/m/Y', strtotime($consulta->getDataInicio())) . "</strong><br>"
                        . "e irá terminar às <strong>" . $consulta->getHoraEncerramento() . "</strong> do dia <strong>" . date('d/m/Y', strtotime($consulta->getDataEncerramento())) . "</strong>.<br>"
                        . "Seu link para votação é: <a href='" . $request->getUri()->getHost() . "/votacao/" . $consulta->getIdVotacao() . "'>" . $consulta->getDescricao() . "</a> <br>"
                        . "Caso não consiga acessar o link, copie e cole o endereço no seu navegador.<br>"
                        . $request->getUri()->getHost() . "/votacao/" . $consulta->getIdVotacao() . "<br>"
                        . "Seu usuario é: <strong>" . $eleitor['idVotacao'] . "</strong><br>"
                        . "Sua senha é: <strong>" . $eleitor['senhaVotacao'] . "</strong><br>"
                        . "Contamos com seu voto para decidir o futuro da UFF.<br>"
                        . "Atenciosamente, <br>"
                        . "Presidente da Comissão Eleitoral";
                $mail->isHTML(true);
                $mail->Body = $mensagem;
                try {
                    // Tente enviar o email
                    if($mail->send()){
                        $sucesso = true;
                    }
                    echo 'Email enviado com sucesso!';

                } catch (Exception $e) {
                    // Se ocorrer um erro, exiba uma mensagem de erro
                    echo 'Erro ao enviar email: ' . $mail->ErrorInfo;
                    $_SESSION['erro'] = 'mensagem';
                    $_SESSION['corpoMensagem'] = 'Não foi possível enviar os links! Verifique os dados e tente novamente.';
                }
            }
            if ($sucesso) {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Links enviados com sucesso!';
                return $response->withHeader('Location', '/consulta/' . $id)->withStatus(302);
            } else {
                $_SESSION['erro'] = 'mensagem';
                $_SESSION['corpoMensagem'] = 'Não foi possível enviar os links! Verifique os dados e tente novamente.';
                return $response->withHeader('Location', '/consulta')->withStatus(302);
            }
        } else {
            $_SESSION['erro'] = 'mensagem';
            $_SESSION['corpoMensagem'] = 'Não foi possível completar sua solicitação! Verifique os dados e tente novamente.';
            return $response->withHeader('Location', '/consulta')->withStatus(302);
        }
    }
}