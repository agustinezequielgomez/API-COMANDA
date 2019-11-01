<?php
namespace Models;
class importe extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "importes";
    public $timestamps = false;
    protected $fillable = array('id_mesa','id_pedido','importe');
}
?>