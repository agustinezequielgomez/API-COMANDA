<?php
namespace Helpers;

use Firebase\JWT\JWT;

class JWTAuth
{
  private static $key = "Multitask4013";
  private static $encryption = ['HS256'];
  private static $aud = null;


  public static function CreateToken($datos)
  {
    $payload = array(
      'iat' => time(),
      'exp' => time() + (6000),
      'aud' => self::Aud(),
      'data' => $datos,
      'app' => "Api Rest Haedo Jonathan"

    );
    //return password_hash(JWT::encode($payload, self::key), PASSWORD_DEFAULT);
    return JWT::encode($payload, self::$key);
  }
  
  /**
   * VerifyToken 
   * 
   * @param mixed $token 
   * @access public
   * @return void
   */
  public static function VerifyToken($token)
  {
    //empty token
    if(empty($token) || is_null($token))
      throw new Exception("invalid token");

    $decoded = null;

    //try decode it
    try
    {
      $decoded = JWT::decode($token, self::$key, self::$encryption);
    }
    catch(\Exception $e)
    {
      //throw $e;
    }

    //compare audits
    if($decoded->aud !== self::Aud())
      return false;
      //throw new Exception("mismatched audits");

    return true;
  }

  public static function Aud()
  {
    $aud = '';
    if(!empty($_SERVER['HTTP_CLIENT_IP']))
    {
     $aud = $_SERVER['HTTP_CLIENT_IP']; 
    }
    else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      $aud = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    }
    else
    {
      $aud = $_SERVER['REMOTE_ADDR'];
    }

    $aud .= @$_SERVER['HTTP_USER_AGENT'];
    $aud .= gethostname();

    return sha1($aud);
  }

  public static function GetPayload($token)
  {
    try
    {
      return JWT::decode($token, self::$key, self::$encryption);
    }
    catch(\Exception $e)
    {
      return null;
    }
  }

  public static function GetData($token)
  {
    $data = self::GetPayload($token);
    if(is_null($data))
    {
      echo '<br>Debe Logearse';
    }
    else
    {
      return is_null($data->data) ? null : $data->data;
    }
  }
}
