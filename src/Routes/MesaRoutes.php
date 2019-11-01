<?php
use Slim\App;
use Controllers\MesaController;
use Middleware\MWComanda;
return function(App $app)
{
    $app->group('/Mesa',function()
    {
        $this->get('/', MesaController::class.':GetMesas');
        $this->post('/',MesaController::class.':EnviarUno');
        $this->post('/Cobro',MesaController::class.':cobrarMesa')->add(MWComanda::class.':MWValidarMesa');
        $this->post('/Cierre',MesaController::class.':cierreMesa')->add(MWComanda::class.':MWValidarMesa');
    })->add(MWComanda::class.':MWVerificarCredenciales')->add(MWComanda::class.':MWVerificarToken');
}
?>