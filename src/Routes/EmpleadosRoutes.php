<?php
namespace Routes;
use Slim\App;
use Controllers\EmpleadosController;
use Middleware\MWComanda;
return function(App $app)
{
    $app->group('/Empleados',function()
    {
        $this->get('/',EmpleadosController::class.':TraerTodos');
        $this->post('/', EmpleadosController::class.':EnviarUno')->add(MWComanda::class.':MWValidarAlta');
        $this->get('/{id}',EmpleadosController::class.':TraerUno')->add(MWComanda::class.':MWValidarIdExistenteGet');
        $this->post('/Modificar',EmpleadosController::class.':ModificarUno')->add(MWComanda::class.':MWValidarAlta')->add(MWComanda::class.':MWValidarIdExistenteNoGet');
        $this->post('/Eliminar',EmpleadosController::class.':BorrarUno')->add(MWComanda::class.':MWValidarIdExistenteNoGet');
        $this->post('/Suspender',EmpleadosController::class.':SuspenderEmpleado')->add(MWComanda::class.':MWValidarIdExistenteNoGet');
        $this->post('/Activar',EmpleadosController::class.':ActivarEmpleado')->add(MWComanda::class.':MWValidarIdExistenteNoGet');

    })->add(MWComanda::class.':MWVerificarCredenciales')->add(MWComanda::class.':MWVerificarToken');
}
?>