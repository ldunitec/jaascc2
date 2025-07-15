<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    
    protected $fillable = ['cliente_id','monto','recibo','metodo_pago','referencia'];

     public function cliente()
{
    return $this->hasOne(Cliente::class);
}
     public function pago()
{
    return $this->hasOne(Pago::class);
}
}
