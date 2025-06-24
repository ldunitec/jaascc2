<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {

    protected $fillable = ['nombre','correo','telefono','direccion','activo'];

    public function mensualidades() {
        return $this->hasMany(Mensualidad::class);
    }

    public function recibos() {
        return $this->hasMany(Recibo::class);
    }

    public function plan() {
    return $this->belongsTo(Plan::class);
}

    
}
