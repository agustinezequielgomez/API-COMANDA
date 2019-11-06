<?php
namespace Models;
class menu extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "menus";
    public $timestamps = false;

    static function verificarAlimentoExistente($alimentos)
    {
        $tiposDeAlimento = array_keys($alimentos);
        $alimentos_menu = menu::all();
        $noEncontrado = false;
        $alimentosNoEncontrados = [];
        foreach($tiposDeAlimento as $tipoDeAlimento)
        {
            foreach($alimentos[$tipoDeAlimento] as $alimento)
            {
                $flag = false;
                foreach($alimentos_menu as $alimento_menu)
                {
                    if(strtolower($alimento) == strtolower($alimento_menu->nombre) && strtolower($alimento_menu->tipo)==strtolower($tipoDeAlimento))
                    {
                        $flag = true;
                    }
                }
                
                if($flag == false)
                {
                    $noEncontrado = true;
                    array_push($alimentosNoEncontrados,$alimento);
                }
            }
        }
        if($noEncontrado == true)
        {
            return $alimentosNoEncontrados;
        }
        else
        {
            return true;
        }
    }

    public function subirFoto($archivos, $path)
    {
        $nombreFoto = ($archivos["foto"])->getClientFileName();
        $extension = explode(".",$nombreFoto);
        $extension = array_reverse($extension)[0];
        $titulo = ("Menu_".$this->nombre."_".$this->tipo.'.'.$extension);
        return $titulo;
    }
}
?>