<?php
namespace Routes;
use Controllers\EmpleadosController;
use Middleware\MWComanda;
use Slim\App;
return function(App $app) 
{
    $app->group('/Login', function()
    {
        $this->post('/',EmpleadosController::class.':Login')->add(MWComanda::class.':MWLogin');
    });

    $app->group('/Register', function()
    {
        $this->post('/', EmpleadosController::class.':EnviarUno')->add(MWComanda::class.':MWValidarAlta');
    });
}
?>