<?php
namespace Controllers;
use Slim\Http\Request;
use Slim\Http\Response;
use Models\logueo;
class LogueosController
{
    function TraerTodos(Request $request,Response $response,$args)
    {
        return $response->withJson(logueo::all(), 200);
    }
}
?>