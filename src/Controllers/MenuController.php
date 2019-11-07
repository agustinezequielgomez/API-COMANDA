<?php
namespace Controllers;
use Slim\Http\Request;
use Slim\Http\Response;
use Models\menu;
class MenuController
{
    public function TraerTodos(Request $request,Response $response,$args)
    {
        return (menu::all())->toJson();
    }

    public function EnviarUno(Request $request,Response $response,$args)
    {
        $menu = new menu();
        $menu->nombre = strtolower($request->getParsedBody()["nombre"]);
        $menu->tipo = strtolower($request->getParsedBody()["tipo"]);
        $menu->precio = $request->getParsedBody()["precio"];
        $menu->path = $menu->subirFoto($request->getUploadedFiles(), "./public_html/img/Menu/");
        $menu->save();
        return $response->withJson($menu->path, 200);
    }

    public function ModificarUno(Request $request,Response $response,$args)
    {
        $menu = $request->getAttribute('menu');
        $menu->nombre = $request->getParsedBody()["nombre"];
        $menu->tipo = $request->getParsedBody()["tipo"];
        $menu->precio = $request->getParsedBody()["precio"];
        $menu->save();
        return $response->withJson("Alimento del menu modificado exitosamente", 200);
    }

    public function BorrarUno(Request $request,Response $response,$args)
    {
        $menu = $request->getAttribute('menu');
        menu::destroy($menu->id);
        return $response->withJson("Alimento eliminado del menu exitosamente", 200);
    }
}

?>