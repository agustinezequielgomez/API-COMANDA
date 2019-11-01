<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Helpers\AppConfig as Config;
use Illuminate\Database\Capsule\Manager as Capsule;
class User extends Model
{
  protected $table = "usuarios";
  public $timestamps = false;
  
  public static function LastInsertId()
  {
      return User::select("id")->orderBy("id", "desc")->first()->id;
  }
  public static function FindByUsername($nombre)
  {
     return User::where("nombre", $nombre)->first();
  }
  public static function FindByUsernameAndPassword($nombre, $clave)
  {
     return User::where("nombre", $nombre)->where("clave", $clave)->first();
  }
}