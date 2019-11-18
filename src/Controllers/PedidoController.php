<?php
namespace Controllers;
use Helpers\VerificadorJWT;
use Controllers\AlimentoController;
use Slim\Http\Request;
use Slim\Http\Response;
use Models\pedido;
use Models\alimento;

class PedidoController
{
    function TraerUno(Request $request,Response $response,$args)
    {
        return (pedido::find($args['id']))->toJson();
    }

    function CancelarUno(Request $request, Response $response, $args)
    {
        $id_pedido = $request->getAttribute("id_pedido");
        $id_mesa = pedido::where('id', $id_pedido)->first(['n_mesa']);
        var_dump($id_mesa);
        die();
        pedido::where('id',$id_pedido)->update(['estado'=>'Cancelado']);
        $request = $request->withAttribute('id_pedido',$id_pedido);
        AlimentoController::cancelarAlimentos($request,$response,$args);
        $request = $request->withAttribute('id_mesa', $id_mesa);
        MesaController::cierreMesa($request, $response, $args);
        return $response->withJson("Pedido cancelado exitosamente", 200);
    }
    
    function EnviarUno(Request $request,Response $response, $args)
    {
        $tokenMozo = $request->getHeader('token')[0];
        $idMozo = (VerificadorJWT::TraerData($tokenMozo))->id;
        $atributos = $request->getParsedBody();
        $alimentos = pedido::procesarPedidos($atributos);
        $pedido = new pedido(['n_mesa'=>$atributos["n_mesa"],'estado'=>"Pendiente","codigo_pedido"=>pedido::generarCodigoDePedido(),"id_empleado"=>$idMozo,"importe"=>pedido::calcularImporte($alimentos),"pedido_realizado"=>date('H:i:s')]);
        $pedido->foto = $pedido->subirFoto($request->getUploadedFiles(),"./public_html/img/Pedidos/");
        $pedido->save();
        $request = $request->withAttributes(["id_mesa"=>$atributos["n_mesa"],"estado"=>"con cliente esperando pedido",'id_pedido'=>$pedido->id]);
        MesaController::ActualizarEstado($request,$response,$args);
        alimento::cargarAlimentos($alimentos,$pedido);
        return $response->withJson(["codigo" => $pedido->codigo_pedido, "foto" => $pedido->foto], 200, JSON_FORCE_OBJECT);
    }

    function ConsultarTiempoEstimado(Request $request,Response $response, $args)
    {
        $pedido = $request->getAttribute('pedido');
        $tiempo_estimado = \DateTime::createFromFormat('H:i:s',$pedido->tiempo_estimado);
        $ahora = \DateTime::createFromFormat('H:i:s',date('H:i:s'));
        if($pedido->tiempo_estimado == '00:00:00')
        {
            return $response->withJson("Su pedido aun no comenzo a prepararse", 200);
        }
        else if($ahora<$tiempo_estimado)
        {
            $interval = $tiempo_estimado->diff($ahora);
            return $response->withJson("Faltan ".$interval->format('%i')." minutos para que tu pedido este listo!", 200);
        }
        else
        {
            return $response->withJson("Tu pedido va a estar listo en breve!", 200);
        }
        return $response;
    }
    
    static function actualizarEstadoPedido(Request $request, Response $response, $args)
    {
        $id = $request->getAttribute('id');
        $estado = $request->getAttribute('estado');
        $tiempo_estimado = $request->getAttribute('estimado');
        switch($estado)
        {
            case "En preparacion":
            if((pedido::select('estado')->where('id',$id)->get())[0]->estado!="En preparacion")
            {
                pedido::where('id',$id)->update(['estado'=>$estado,'pedido_en_preparacion'=>date('H:i:s'),'tiempo_estimado'=>$tiempo_estimado]);
            }
            else if(pedido::verificarEstimadoMaximo($id,$tiempo_estimado)==true)
            {
                pedido::where('id',$id)->update(['tiempo_estimado'=>$tiempo_estimado]);
            }
            break;

            case "Listo para servir":
            pedido::where('id',$id)->update(['estado'=>$estado,'pedido_listo_para_servir'=>date('H:i:s')]);
            break;
        }
    }

    function TraerTodos(Request $request,Response $response, $args)
    {
        return $response->withJson(pedido::all(), 200);
    }

    static function entregarPedido(Request $request,Response $response, $args)
    {
        $id = $request->getAttribute('id_pedido');
        pedido::where('id',$id)->update(["estado"=>"Entregado","pedido_entregado"=>date('H:i:s')]);
        $request = $request->withAttributes(["id_mesa"=>(pedido::select('n_mesa')->where('id',$id)->get())[0]->n_mesa,"estado"=>"con cliente comiendo",'id_pedido'=>$id]);
        mesaApi::ActualizarEstado($request,$response,$args);
        return $response->withJson("Pedido entregado con exito", 200);
    }
}
?>