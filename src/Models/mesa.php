<?php
namespace Models;
class mesa extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "mesas";
    public $timestamps = false;

    protected $fillable = array('estado','id_pedido');
}
?>