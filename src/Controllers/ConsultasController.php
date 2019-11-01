<?php
namespace Controllers;
use Slim\Http\Request;
use Slim\Http\Response;
use Models\alimento;
use Models\pedido;
use Helpers\consultas;

class ConsultasController
{
    function ListarCantidadDeOperaciones(Request $request, Response $response, array $args)
    {
        $opcion = $request->getParam('opcion');
        switch($opcion)
        {
            case 1:
            $operaciones = consultas::OperacionesPorSector();
            return $response->withJson("Cantidad de operaciones de la cocina: ".$operaciones["cocina"].
            "<br>Cantidad de operaciones de la barra de tragos: ".$operaciones["barra de tragos"].
            "<br>Cantidad de operaciones de la barra de choperas: ".$operaciones["barra de choperas"].
            "<br>Cantidad de operaciones del candy bar: ".$operaciones["candy bar"]."<br>Cantidad total de operaciones: ".alimento::where('estado','!=','Pendiente')->count(), 200);
            break;

            case 2:
            $operacionesPorEmpleadoYSector = consultas::OperacionesPorSectorYEmpleado();
            return $response->withJson($operacionesPorEmpleadoYSector, 200);
            break;

            case 3:
            $operacionesPorEmpleado = consultas::OperacionesPorEmpleado();
            $retorno = "";
            foreach($operacionesPorEmpleado as $operaciones)
            {
                $retorno .= $operaciones;
            }
            return $response->withJson($retorno, 200);
            break;
        }
        return $response;
    }

    function ListarAlimentosVendidos(Request $request, Response $response, array $args)
    {
        $cantidad = $request->getParam('cantidad');
        $opcion = $request->getParam('opcion');
        switch($opcion)
        {
            case 1:
            case 2:
            $alimentosVendidos = consultas::AlimentosVendidos();
            $recorridos = 0;
            $retorno = "";
            if($opcion == 1)
            {
                arsort($alimentosVendidos);
            }
            else
            {
                asort($alimentosVendidos);
            }
            foreach($alimentosVendidos as $alimento=>$vecesVendido)
            {
                if($recorridos == $cantidad)
                {
                    break;
                }
                $retorno .= ("Alimento: ".$alimento.". Veces vendido: ".$vecesVendido."<br>");
                $recorridos++;
            }
            return $response->withJson($retorno,200);
            break;

            case 3:
            return $response->withJson(json_encode(consultas::PedidosQueTardaronDeMas()),200);
            break;

            case 4:
            return $response->withJson(json_encode(pedido::where('estado','Cancelado')->get()),200);
            break;
        }
    }

    function ListarMesas(Request $request, Response $response, array $args)
    {
        $cantidad = $request->getParam('cantidad');
        $opcion = $request->getParam('opcion');
        switch($opcion)
        {
            case 1:
            case 2:
            $mesasUsadas = consultas::MesaUsada();
            $recorridos = 0;
            $retorno = "";
            if($opcion == 1)
            {
                arsort($mesasUsadas);
            }
            else
            {
                asort($mesasUsadas);
            }
            foreach($mesasUsadas as $mesa=>$vecesUsada)
            {
                if($recorridos == $cantidad)
                {
                    break;
                }
                $retorno .= ("Mesa: ".$mesa.". Veces usada: ".$vecesUsada."<br>");
                $recorridos++;
            }
            return $response->withJson($retorno, 200);
            break;

            case 3:
            case 4:
            $facturacionMesas = consultas::MesaFacturacionAcumulada();
            $recorridos = 0;
            $retorno = "";
            if($opcion == 3)
            {
                arsort($facturacionMesas);
            }
            else
            {
                asort($facturacionMesas);
            }
            foreach($facturacionMesas as $mesa=>$DineroRecaudado)
            {
                if($recorridos == $cantidad)
                {
                    break;
                }
                $retorno .= ("Mesa: ".$mesa.". Facturacion acumulada: ".$DineroRecaudado."<br>");
                $recorridos++;
            }
            return $response->withJson($retorno, 200);
            break;

            case 5:
            case 6:
            $facturacionMesas = consultas::MesaMayorFacturacion();
            $recorridos = 0;
            $retorno = "";
            if($opcion == 5)
            {
                arsort($facturacionMesas);
            }
            else
            {
                asort($facturacionMesas);
            }
            foreach($facturacionMesas as $mesa=>$DineroRecaudado)
            {
                if($recorridos == $cantidad)
                {
                    break;
                }
                $retorno .= ("Mesa: ".$mesa.". Facturacion: ".$DineroRecaudado."<br>");
                $recorridos++;
            }
            return $response->withJson($retorno, 200);
            break;

        }
    }

    function ImportesTotales(Request $request, Response $response, array $args)
    {
        return $response->withJson(consultas::ImportesTotales(), 200);
    }
}
?>