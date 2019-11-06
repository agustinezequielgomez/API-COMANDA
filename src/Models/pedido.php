<?php
namespace Models;
class pedido extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "pedidos";
    public $timestamps = false;

    protected $fillable = array('n_mesa','estado','codigo_pedido','id_empleado','importe','foto','pedido_realizado');

    public static function generarCodigoDePedido() 
    { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, 5); 
    } 

    public static function procesarPedidos($pedidos)
    {
        $pedidosParsed = array();
        $alimentos = ["comida","vino","trago","cerveza","postre"];
        foreach($alimentos as $alimento)
        {
            if($pedidos[$alimento]!=NULL)
            {
                $pedidosParsed[$alimento] = explode(", ",$pedidos[$alimento]);
            }
            else
            {
                $pedidosParsed[$alimento] = [];
            }
        }
        return $pedidosParsed;
    }

    public static function calcularImporte($alimentos)
    {
        $precioTotal = 0;
        $tiposDeAlimento = array_keys($alimentos);
        foreach($tiposDeAlimento as $tipoDeAlimento)
        {
            foreach($alimentos[$tipoDeAlimento] as $alimento)
            {
                $precioTotal += (menu::where('tipo',$tipoDeAlimento)->where('nombre',$alimento)->first())->precio;
            }
        }
        return $precioTotal;
    }

    public function subirFoto($archivos,$path)
    {
        $nombreFoto = ($archivos["foto"])->getClientFileName();
        $extension = explode(".",$nombreFoto);
        $extension = array_reverse($extension)[0];
        $titulo = ("Mesa_".$this->n_mesa."_Pedido_".$this->codigo_pedido.'.'.$extension);
        return $titulo;
    }

    public static function verificarEstimadoMaximo($id, $tiempo_estimado)
    {
        $tiempo_estimado = \DateTime::createFromFormat('H:i:s',$tiempo_estimado);
        $tiempo_estimado_actual = \DateTime::createFromFormat('H:i:s',(pedido::select('tiempo_estimado')->where('id',$id)->get()[0])->tiempo_estimado);
        return ($tiempo_estimado>$tiempo_estimado_actual);
    }

}
?>