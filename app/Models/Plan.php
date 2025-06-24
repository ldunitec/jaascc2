<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['nombre', 'monto', 'descripcion'];

    public function clientes() {
        return $this->hasMany(Cliente::class);
    }
}
