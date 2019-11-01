<?php
use Slim\App;
use Controllers\ConsultasController;
use Middleware\MWComanda;
return function(App $app)
{
    $app->group('/Consultas',function()
    {
        $this->get('/Empleados',ConsultasController::class.':ListarCantidadDeOperaciones');
        $this->get('/Pedidos',ConsultasController::class.':ListarAlimentosVendidos');
        $this->get('/Mesas',ConsultasController::class.':ListarMesas');
        $this->get('/ImportesTotales', ConsultasController::class.':ImportesTotales');
    })->add(MWComanda::class.':MWVerificarCredenciales')->add(MWComanda::class.':MWVerificarToken');
}
?>