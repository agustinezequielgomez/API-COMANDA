<?php
namespace Models;
use Models\empleado;
class alimento extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "alimentos";
    public $timestamps = false;

    protected $fillable = array('id_pedido','tipo','nombre_alimento','estado','tiempo_comienzo','tiempo_estimado','tiempo_final','id_empleado');
    static function cargarAlimentos($alimentos, $pedido)
    {
        $tiposAlimento = array_keys($alimentos);
        foreach($tiposAlimento as $tipoAlimento)
        {
            foreach($alimentos[$tipoAlimento] as $alimentoPedido)
            {
                $alimento = new alimento(["id_pedido"=>$pedido->id,"tipo"=>$tipoAlimento,"nombre_alimento"=>$alimentoPedido,"estado"=>"Pendiente"]);
                $alimento->save();
            }            
        }
    }

    static function obtenerAlimentoBasadoEnPuesto($puestoEmpleado)
    {
        $respuesta;
        $alimento = new alimento();
        switch($puestoEmpleado)
        {
            case "bartender":
            $respuesta = ($alimento->where('tipo','=','vino')->orWhere('tipo','=','trago')->where('estado','Pendiente'))->get();
            break;

            case "cervecero":
            $respuesta = $alimento->where('tipo','=','cerveza')->where('estado','Pendiente')->get();
            break;

            case "cocinero":
            $respuesta = $alimento->where('tipo','=','comida')->orWhere('tipo','postre')->where('estado','Pendiente')->get();
            break;

            case "socio":
            $respuesta = $alimento::all();
            break;
        }
        return $respuesta;
    }

    static function calcularTiempoEstimado($puestoEmpleado,$tiempo_estimado)
    {
        $disponibles = empleado::BuscarEmpleadoDisponible($puestoEmpleado);
        $estimado = $tiempo_estimado/$disponibles;
        return date('H:i:s',strtotime('+'.round($estimado).' minutes',strtotime(date('H:i:s'))));
    }

    static function verificarAlimentosListos($id_pedido)
    {
        $alimentos = alimento::where('id_pedido',$id_pedido)->get();
        $flag = true;
        foreach($alimentos as $alimento)
        {
            if($alimento->estado != "Listo para servir")
            {
                $flag = false;
                break;
            }
        }
        return $flag;
    }
}
?>