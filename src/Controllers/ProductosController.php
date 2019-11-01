<?php

namespace Controllers;

use Models\Productos;
use Helpers\JWTAuth;
use Helpers\AppConfig as Config;
use Helpers\FilesHelper as Files;
use Helpers\ImagesHelper as Images;
use Illuminate\Database\Capsule\Manager as Capsule;

class ProductosController
{
  public static function traerTodos($request, $response, $args)
  {
    return json_encode(Productos::all());  
  }
  public static function traerPorId($request, $response, $args)
  {
    $id = $request->getAttributes()["id"];
    $producto = Productos::find($id);
    if($producto)
    {
      $responseObj = ["message" => "producto encontrado", "producto" => $producto];
      //return $response->withJson(json_encode($responseObj), 200);
      return json_encode($producto);  
    }
    else
    {
      $responseObj = ["message" => "producto no encontrada"];
      return $response->withJson(json_encode($responseObj), 401);
    }
  }

  public static function Create($request, $response, $args)
  {
    $data = $request->getParsedBody(); 

    $producto = new Productos;
    $producto->descripcion = $data["descripcion"];
    $producto->tipo = $data["tipo"];
    $producto->fechaDeVencimiento = $data["fechaDeVencimiento"];
    $producto->precio = $data["precio"];
    $producto->rutaDeFoto = $data["path"];
    $producto->save();

    $responseObj = ["message" => "producto creado", "producto" => $producto];
    return $response->withJson(json_encode($producto), 200);
  }

  public static function Update($request, $response, $args)
  {
    $data = $request->getParsedBody();
    $producto = Productos::find($args["id"]);
    if(!$producto)
    {
      return $response->withJson("producto inexistente", 200);
    }
    $producto->descripcion = $data["descripcion"];
    $producto->tipo = $data["tipo"];
    $producto->fechaDeVencimiento = $data["fechaDeVencimiento"];
    $producto->precio = $data["precio"];
    $producto->rutaDeFoto = $data["path"];
    $producto->save();

    return $response->withJson("producto actualizado", 200);
  }

  public static function Delete($request, $response, $args)
  {
    if(!isset($args["id"]))
    {
      return $response->withJson("debe especificar id", 400);
    }
    $producto = Productos::find($args["id"]);
    if(!$producto)
    {
      return $response->withJson("producto inexistente", 200);
    }
    $producto->delete();
    return $response->withJson("producto eliminado");
  }
}