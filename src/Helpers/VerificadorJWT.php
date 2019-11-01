<?php
namespace Helpers;
use \Firebase\JWT\JWT;
class VerificadorJWT
{
    private static $clave = 'comanda123';
    private static $encoding = ['HS256'];

    public static function crearToken($datos)
    {
        $payload = array(
            'iat'=>time(),
            'exp'=>time()+5000,
            'aud'=>self::aud(),
            'data'=>$datos,
            'app'=>"Comanda 2019"
        );
        return JWT::encode($payload,self::$clave);
    }

    public static function VerificarToken($token)
    {
        if(empty($token))
        {
            throw new \Exception("El token esta vacio");
        }

        try
        {
            $decodificado = JWT::decode($token,self::$clave,self::$encoding);
        }
        catch(\Exception $e)
        {
            throw $e;
        }

        if($decodificado->aud != self::aud())
        {
            throw new \Exception("El token fue modificado. No es valido");
        }
    }

    public static function TraerPayload($token)
    {
        return JWT::decode($token,self::$clave,self::$encoding);
    }

    public static function TraerData($token)
    {
        return self::TraerPayload($token)->data;
    }

    private static function aud()
    {
        $aud = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        
        return sha1($aud);
    }
}
?>