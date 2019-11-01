<?php
namespace Models;
class rate extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "rates";
    public $timestamps = false;
    protected $fillable = array('id_pedido','id_mesa','rate_mesa','rate_mozo','rate_cocinero','rate_restaurant','comentario');
}
?>