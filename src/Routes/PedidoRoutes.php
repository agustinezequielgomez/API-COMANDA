<?php
namespace Routes;
use Slim\App;
use Middleware\MWComanda;
use Controllers\PedidoController;
return function(App $app)
{
    $app->group('/Pedidos',function()
    {
        $this->get('/',PedidoController::class.':TraerTodos');
        $this->get('/TiempoEstimado',PedidoController::class.':ConsultarTiempoEstimado')->add(MWComanda::class.':MWValidarCodigoDePedidoExistente');
        $this->get('/{id}',PedidoController::class.':TraerUno');
        $this->post('/',PedidoController::class.':EnviarUno')->add(MWComanda::class.':MWValidarComidaExistente')->add(MWComanda::class.':MWValidarMesa');
        $this->post('/Cancelar',PedidoController::class.':CancelarUno')->add(MWComanda::class.':MWValidarPedidoExistente');
        $this->post('/Entregar',PedidoController::class.':entregarPedido')->add(MWComanda::class.':MWValidarEntregaPedido')->add(MWComanda::class.':MWValidarPedidoExistente');
    })->add(MWComanda::class.':MWVerificarCredenciales')->add(MWComanda::class.':MWVerificarToken');
}
?>