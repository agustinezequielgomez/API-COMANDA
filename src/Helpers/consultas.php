<?php
namespace Helpers;
use Models\alimento;
use Models\empleado;
use Models\menu; 
use Models\pedido;
use Models\mesa;
use Models\importe;

class consultas
{
    static function OperacionesPorSector()
    {
        $operaciones = [];
        $operaciones["cocina"] = (alimento::where('tipo','comida')->where('estado','!=','Pendiente')->get())->count();
        $operaciones["barra de tragos"] = (alimento::where('tipo','trago')->orWhere('tipo','vino')->where('estado','!=','Pendiente')->get())->count();
        $operaciones["barra de choperas"] = (alimento::where('tipo','cerveza')->where('estado','!=','Pendiente')->get())->count();
        $operaciones["candy bar"] = (alimento::where('tipo','postre')->where('estado','!=','Pendiente')->get())->count();
        return $operaciones;
    }

    static function OperacionesPorSectorYEmpleado()
    {
        $sectores = ["cocina","barra de tragos","barra de choperas","candy bar"];
        $operaciones = consultas::OperacionesPorSector();
        $retorno = "";
        foreach($sectores as $sector)
        {
            $operacionesEmpleado=[];
            switch($sector)
            {
                case "cocina":
                $empleados = empleado::where('tipo','cocinero')->get();
                foreach($empleados as $empleado)
                {
                    array_push($operacionesEmpleado,("Empleado: ".$empleado->nombre.". Cantidad de operaciones: ".(alimento::where('id_empleado',$empleado->id)->where('tipo','comida')->get())->count()."<br>"));
                }
                break;

                case "barra de tragos":
                $empleados = empleado::where('tipo','bartender')->get();
                foreach($empleados as $empleado)
                {
                    array_push($operacionesEmpleado,("Empleado: ".$empleado->nombre.". Cantidad de operaciones: ".(alimento::where('id_empleado',$empleado->id)->get())->count()."<br>"));
                }
                break;

                case "barra de choperas":
                $empleados = empleado::where('tipo','cervecero')->get();
                foreach($empleados as $empleado)
                {
                    array_push($operacionesEmpleado,("Empleado: ".$empleado->nombre.". Cantidad de operaciones: ".(alimento::where('id_empleado',$empleado->id)->get())->count()."<br>"));
                }
                break;

                case "candy bar":
                $empleados = empleado::where('tipo','cocinero')->get();
                foreach($empleados as $empleado)
                {
                    array_push($operacionesEmpleado,("Empleado: ".$empleado->nombre.". Cantidad de operaciones: ".(alimento::where('id_empleado',$empleado->id)->where('tipo','postre')->get())->count()."<br>"));
                }
                break;
            }
            $retorno .= ("<br>Cantidad de operaciones de ".$sector.": ".$operaciones[$sector]."<br>Operaciones por empleado: <br>");
            foreach($operacionesEmpleado as $operacionEmpleado)
            {
                $retorno .= ($operacionEmpleado);
            }
        }
        return $retorno;
    }

    static function OperacionesPorEmpleado()
    {
        $empleados = empleado::all();
        $operaciones = [];
        foreach($empleados as $empleado)
        {
            if($empleado->tipo == "socio" || $empleado->tipo == "administrador" || $empleado->tipo == "mozo")
            {
                continue;
            }
            array_push($operaciones,"Empleado: ".$empleado->nombre.". Cantidad de operaciones: ".(alimento::where('id_empleado',$empleado->id)->get())->count()."<br>");
        }
        return $operaciones;
    }

    static function AlimentosVendidos()
    {
        $alimentosMenu = menu::all();
        $cantidadDeVendidos = [];
        foreach($alimentosMenu as $alimento)
        {
            $cantidadDeVendidos[$alimento->nombre] = (alimento::where('nombre_alimento',$alimento->nombre)->where('estado','!=','Cancelado')->get())->count();
        }
        return $cantidadDeVendidos;
    }

    static function PedidosQueTardaronDeMas()
    {
        $pedidos = pedido::all();
        $pedidosDemorados = [];
        foreach($pedidos as $pedido)
        {
            if(\DateTime::createFromFormat('H:i:s',$pedido->pedido_entregado) > \DateTime::createFromFormat('H:i:s',$pedido->tiempo_estimado))
            {
                array_push($pedidosDemorados,$pedido);
            }
        }
        return $pedidosDemorados;
    }

    static function MesaUsada()
    {
        $mesas = mesa::all();
        $usoMesas = [];
        foreach($mesas as $mesa)
        {
            $usoMesas[$mesa->id] = (pedido::where('n_mesa',$mesa->id)->get())->count();
        }
        return $usoMesas;
    }

    static function MesaFacturacionAcumulada()
    {
        $mesas = mesa::all();
        $importePorMesa = [];
        foreach($mesas as $mesa)
        {
            $importePorMesa[$mesa->id] = importe::where('id_mesa',$mesa->id)->sum('importe');
        }
        return $importePorMesa;
    }

    static function MesaMayorFacturacion()
    {
        $importes = importe::all();
        $facturacionPorMesa = [];
        foreach($importes as $importe)
        {
            $facturacionPorMesa[$importe->id_mesa] = $importe->importe;
        }
        return $facturacionPorMesa;
    }

    static function ImportesTotales()
    {
        $importes = importe::all();
        $importesTotales = 0;
        foreach($importes as $importe)
        {
            $importesTotales += $importe->importe;
        }
        return $importesTotales;
    }
}
?>