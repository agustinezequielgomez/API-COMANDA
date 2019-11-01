<?php
use Slim\App;
use Middleware\MWComanda;
use Controllers\LogueosController;
return function(App $app)
{
    $app->group('/Registros',function()
    {
        $this->get('/', LogueosController::class.':TraerTodos');
    })->add(MWComanda::class.':MWVerificarCredenciales')->add(MWComanda::class.':MWVerificarToken');
}
?>