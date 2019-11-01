<?php
namespace Controllers;
use Helpers\VerificadorJWT;
use Slim\Http\Request;
use Slim\Http\Response;
use Models\mesa;
use Models\pedido;

class MesaController
{
    function GetMesas(Request $request, Response $response, array $args)
    {
        return $response->withJson(mesa::all(), 200);
    }

    function EnviarUno(Request $request, Response $response, array $args)
    {
        $mesa = new mesa();
        $mesa->estado = "cerrada";
        $mesa->codigo_identificacion = pedido::generarCodigoDePedido();
        $mesa->save();
        return $response->withJson("Mesa dada de alta", 200);
    }

    static function ActualizarEstado(Request $request, Response $response, array $args)
    {
        $estado = $request->getAttributes()['estado'];
        $id_mesa = $request->getAttributes()['id_mesa'];
        $id_pedido = $request->getAttributes()['id_pedido'];
        mesa::find($id_mesa)->update(["estado"=>$estado,"id_pedido"=>$id_pedido]);
    }

    function cobrarMesa(Request $request,Response $response, $args)
    {
        $id = $request->getAttribute('id_mesa');
        mesa::find($id)->update(["estado"=>"con cliente pagando"]);
        $request = $request->withAttribute('id_pedido',(mesa::select('id_pedido')->where('id',$id)->get())[0]->id_pedido);
        ImporteController::EnviarUno($request,$response,$args);
        return $response->withJson("Cobro realizado con exito", 200);
    }

    function cierreMesa(Request $request,Response $response, $args)
    {
        $id = $request->getAttribute('id_mesa');
        mesa::where('id',$id)->update(["estado"=>"cerrada","id_pedido"=>0]);
        return $response->withJson("Mesa cerrada con exito", 200);
    }
}
?>