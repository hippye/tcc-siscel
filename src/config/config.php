<?php

namespace src\config;

use Psr\Http\Message\RequestInterface;

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


//Configura a conexão com o banco de dados
define('HOST', $config['db']['host'] = 'localhost');
define('USER', $config['db']['user'] = 'siscel');
define('PASS', $config['db']['pass'] = '9tF2QTuRezrF4uB');
define('DBNAME', $config['db']['dbname'] = 'siscel');
define('TIMEOUT', $config['db']['timeout'] = '1800');

//Configurações OAuth2 Google
define('GOOGLE_CLIENT_ID', $config['oauth2']['google']['client_id'] = '422558301435-tu04voi0krousq6e90js1jdlv7vlase9.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', $config['oauth2']['google']['client_secret'] = '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');
define('GOOGLE_REDIRECT_URI', $config['oauth2']['google']['redirect_uri'] = 'http://siscel.vr.uff.br/login/google/callback');

//Define os tipos de arquivos permitidos para upload
define('TIPOS_PERMITIDOS', $config['upload']['tipos_permitidos'] = array('text/csv', 'text/plain'));

//Define os dados para envio de e-mail
define('EMAIL', $config['email']['email'] = 'cesar.daer@gmail.com');
define('SENHA', $config['email']['senha'] = 'rbmjwizivbczbftb');
define('SMTP', $config['email']['smtp'] = 'smtp.gmail.com');
define('PORTA', $config['email']['porta'] = '587');
