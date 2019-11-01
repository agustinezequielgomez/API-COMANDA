<?php
namespace Routes;
use Slim\App;
use Middleware\MWComanda;
use Controllers\AlimentoController;
return function(App $app)
{
    $app->group('/Alimentos',function()
    {
        $this->get('/',AlimentoController::class.':verAlimentos');
        $this->post('/',AlimentoController::class.':prepararAlimento')->add(MWComanda::class.':MWValidarPreparacionDeAlimento')->add(MWComanda::class.':MWValidarPedidoExistente');
        $this->post('/TerminarPreparacion',AlimentoController::class.':terminarPreparacion')->add(MWComanda::class.':MWValidarAlimentosEnPreparacion');//->add(MWComanda::class.':MWValidarPedidoExistente');
    })->add(MWComanda::class.':MWVerificarCredenciales')->add(MWComanda::class.':MWVerificarToken');
}
?>