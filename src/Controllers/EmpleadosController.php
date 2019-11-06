<?php
namespace Controllers;
use Helpers\VerificadorJWT;
use Slim\Http\Request;
use Slim\Http\Response;
use Models\empleado;
use Models\logueo;

class EmpleadosController
{
    function TraerUno(Request $request,Response $response,$args)
    {
        return (empleado::find($args['id']))->toJson();
    }

    function BorrarUno(Request $request, Response $response, $args)
    {
        empleado::destroy($request->getAttribute('id'));
        $response->withJson("Empleado eliminado exitosamente", 200);
        return $response;
    }
    
    function EnviarUno(Request $request,Response $response, $args)
    {
        $empleado = $request->getAttribute('empleado');
        $empleado->estado = "Activo";
        $empleado->path = $empleado->subirFoto($request->getUploadedFiles(), './public_html/img/Empleados/');
        $empleado->tipo = "cliente";
        $empleado->save();
        return $response->withJson($empleado->path, 200);
    }

    function ModificarUno(Request $request,Response $response, $args)
    {
        $empleado = $request->getAttribute('empleado');
        $empleado->save();
        $response->withJson("Empleado modificado exitosamente", 200);
        return $response;
    }
    
    function TraerTodos(Request $request,Response $response, $args)
    {
        return (empleado::all())->toJson();
    }

    function Login(Request $request,Response $response, $args)
    {
        $empleado = $request->getAttribute('empleado');
        logueo::crearLogueo($empleado);
        return $response->withJson(VerificadorJWT::crearToken(["id"=>$empleado->id,"nombre"=>$empleado->nombre,"tipo"=>$empleado->tipo]), 200);
    }

    function SuspenderEmpleado(Request $request,Response $response, $args)
    {
        empleado::where('id',$request->getAttribute('id'))->update(['estado'=>'Suspendido']);
        return $response->withJson("Empleado supendido exitosamente", 200);
    }

    function ActivarEmpleado(Request $request,Response $response, $args)
    {
        empleado::where('id',$request->getAttribute('id'))->update(['estado'=>'Activo']);
        return $response->withJson("Empleado activado exitosamente", 200);
    }
}
?>