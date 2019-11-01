<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ServerreqInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Helpers\AppConfig;

require __DIR__.'/vendor/autoload.php';

$config = ['settings' => ['displayErrorDetails' => true]];

$app = new \Slim\App($config);

date_default_timezone_set('America/Argentina/Buenos_Aires');
$app->add(function ($req, $res, $next) {
    $res = $res->withHeader('Access-Control-Allow-Origin', '*');
    $res = $res->withHeader('Access-Control-Allow-Methods', $req->getHeaderLine('Access-Control-Request-Method'));
    $res = $res->withHeader('Access-Control-Allow-Headers', $req->getHeaderLine('Access-Control-Request-Headers'));

    return $next($req, $res);
});

$capsule = new Capsule;
$capsule->addConnection(AppConfig::$illuminateDb);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$alimentosRoutes = require __DIR__.'/src/Routes/AlimentoRoutes.php';
$alimentosRoutes($app);

$consultasRoutes = require __DIR__.'/src/Routes/ConsultasRoutes.php';
$consultasRoutes($app);

$empleadosRoutes = require __DIR__.'/src/Routes/EmpleadosRoutes.php';
$empleadosRoutes($app);

$loginRoute = require __DIR__.'/src/Routes/LoginRoutes.php';
$loginRoute($app);

$logueosRoutes = require __DIR__.'/src/Routes/LogueoRoutes.php';
$logueosRoutes($app);

$menuroutes = require __DIR__.'/src/Routes/MenuRoutes.php';
$menuroutes($app);

$mesaRoutes = require __DIR__.'/src/Routes/MesaRoutes.php';
$mesaRoutes($app);

$pedidoRoutes = require __DIR__.'/src/Routes/PedidoRoutes.php';
$pedidoRoutes($app);

$rateRoutes = require __DIR__.'/src/Routes/RateRoutes.php';
$rateRoutes($app);

$app->run();
