<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use src\middleware\authMiddleware;
use src\middleware\googleAuthMiddleware;

$googleClient = new Google_Client();
$googleClient->setClientId(GOOGLE_CLIENT_ID);
$googleClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$googleClient->setRedirectUri(GOOGLE_REDIRECT_URI);
$googleClient->addScope('email');
$googleClient->addScope('profile');

$app->get('/login', 'src\controller\loginController:login');
$app->post('/login', 'src\controller\loginController:logar');
$app->get('/login/google', src\controller\loginController::class . ':googleLogin')->setName('google_login');
$app->get('/login/google/callback', src\controller\loginController::class . ':googleCallback')->setName('google_callback');
$app->get('/logout', src\controller\loginController::class . ':logout')->setName('logout');

$app->get('/votacao/login', 'src\controller\votacaoController:login');
$app->post('/votacao/login', 'src\controller\votacaoController:logar');
$app->get('/votacao/erro', 'src\controller\votacaoController:erro');
$app->get('/votacao/logout/{id}', 'src\controller\votacaoController:logout');
$app->get('/votacao/comprovante', 'src\controller\votacaoController:comprovante');
$app->group('/votacao', function (\Slim\Routing\RouteCollectorProxy $group) {
    $group->get('/{id}', 'src\controller\votacaoController:mostrar');
    $group->post('/cedula/{id}', 'src\controller\votacaoController:mostrarCedula');
    $group->post('/revisar/{id}', 'src\controller\votacaoController:revisar');
    $group->post('/confirmar/{id}', 'src\controller\votacaoController:confirmar');
    $group->post('/depositar/{id}', 'src\controller\votacaoController:depositar');
})->addMiddleware(new authMiddleware());

$app->group('', function (\Slim\Routing\RouteCollectorProxy $group) {
    $group->get('/', function (Request $request, Response $response, $args) {
        $erro = '';
        $mensagem = '';
        if (isset($_SESSION['erro']) && $_SESSION['erro'] == 'mensagem') {
            $erro = $_SESSION['erro'];
            $mensagem = $_SESSION['corpoMensagem'];
            unset($_SESSION['erro']);
            unset($_SESSION['corpoMensagem']);
        }
        $view = \Slim\Views\Twig::fromRequest($request);
        return $view->render($response, 'menu.twig', [
            'nome' => $_SESSION['usuario']['nome'],
            'erro' => $erro,
            'mensagem' => $mensagem,
        ]);
    });
    $group->get('/usuario/inserir', 'src\controller\usuarioController:formInserir');
    $group->post('/usuario/inserir', 'src\controller\usuarioController:inserir');
    $group->get('/usuario', 'src\controller\usuarioController:listar');
    $group->get('/usuario/{id}', 'src\controller\usuarioController:editar');
    $group->post('/usuario/{id}', 'src\controller\usuarioController:alterar');
    $group->post('/usuario/{id}/alterarSenha', 'src\controller\usuarioController:alterarSenha');
    $group->get('/consulta', 'src\controller\consultaController:listar');
    $group->get('/consulta/inserir', 'src\controller\consultaController:inserir');
    $group->post('/consulta/inserir', 'src\controller\consultaController:insert');
    $group->get('/consulta/{id}', 'src\controller\consultaController:mostrar');
    $group->post('/consulta/{id}', 'src\controller\consultaController:salvar');
    $group->get('/consulta/{id}/relatorio/zerezimo', 'src\controller\relatorioController:relatorioZerezimo');
    $group->get('/consulta/{id}/relatorio/totalizar', 'src\controller\relatorioController:totalizarVotos');
    $group->get('/consulta/{id}/congelar', 'src\controller\consultaController:congelar');
    $group->get('/consulta/{id}/enviarLink', 'src\controller\consultaController:enviarLink');
    $group->get('/consulta/{id}/excluir', 'src\controller\consultaController:excluir');
    $group->get('/consulta/{id}/membro/{idm}', 'src\controller\membroController:mostrar');
    $group->get('/consulta/{id}/membro/inserir', 'src\controller\membroController:inserir');
    $group->post('/consulta/{id}/membro/inserir', 'src\controller\membroController:insert');
    $group->post('/consulta/{id}/membro/{idm}/alterar', 'src\controller\membroController:alterar');
    $group->get('/consulta/{id}/membro/{idm}/excluir', 'src\controller\membroController:excluir');
    $group->get('/consulta/{id}/chapa/{idc}', 'src\controller\chapaController:mostrar');
    $group->post('/consulta/{id}/chapa/{idc}/alterar', 'src\controller\chapaController:alterar');
    $group->post('/consulta/{id}/chapa/inserir', 'src\controller\chapaController:insert');
    $group->get('/consulta/{id}/chapa/{idc}/excluir', 'src\controller\chapaController:excluir');
    $group->post('/consulta/{id}/proporcao/inserir', 'src\controller\proporcaoController:inserir');
    $group->get('/consulta/{id}/proporcao/{idp}', 'src\controller\proporcaoController:mostrar');
    $group->post('/consulta/{id}/proporcao/{idp}/alterar', 'src\controller\proporcaoController:alterar');
    $group->get('/consulta/{id}/proporcao/{idp}/excluir', 'src\controller\proporcaoController:excluir');
    $group->get('/consulta/{id}/urna/{idu}', 'src\controller\urnaController:mostrar');
    $group->post('/consulta/{id}/urna/{idu}/alterar', 'src\controller\urnaController:alterar');
    $group->post('/consulta/{id}/urna/inserir', 'src\controller\urnaController:inserir');
    $group->post('/consulta/{id}/urna/csv', 'src\controller\urnaController:inserirCsv');
    $group->get('/consulta/{id}/urna/{idu}/excluir', 'src\controller\urnaController:excluir');
    $group->get('/consulta/{id}/eleitor/{ide}', 'src\controller\eleitorController:mostrar');
    $group->post('/consulta/{id}/eleitor/{ide}/alterar', 'src\controller\eleitorController:alterar');
    $group->post('/consulta/{id}/eleitor/inserir', 'src\controller\eleitorController:inserir');
    $group->post('/consulta/{id}/eleitor/csv', 'src\controller\eleitorController:inserirCsv');
    $group->get('/consulta/{id}/eleitor/{ide}/excluir', 'src\controller\eleitorController:excluir');
//})->addMiddleware(new \src\Middleware\authMiddleware());
})->addMiddleware(new googleAuthMiddleware($googleClient));