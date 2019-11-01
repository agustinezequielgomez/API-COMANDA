<?php
use Slim\App;
use Controllers\MenuController;
use Middleware\MWComanda;
return function(App $app)
{
    $app->group('/Menu',function()
    {
        $this->get('/',MenuController::class.':TraerTodos');
        $this->post('/',MenuController::class.':EnviarUno')->add(MWComanda::class.':MWValidarTipoAlimento');
        $this->post('/Modificar',MenuController::class.':ModificarUno')->add(MWComanda::class.':MWValidarTipoAlimento')->add(MWComanda::class.':MWValidarIdAlimentoMenu');
        $this->post('/Eliminar',MenuController::class.':BorrarUno')->add(MWComanda::class.':MWValidarIdAlimentoMenu');
    })->add(MWComanda::class.':MWVerificarCredenciales');
}
?>