<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensualidad extends Model {
    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function recibo() {
        return $this->belongsTo(Recibo::class);
    }
}