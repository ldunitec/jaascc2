<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{

    protected $fillable = ['cliente_id','mes_pago','monto'];


  public function pagos()
{
    return $this->hasMany(Pago::class);
}

public function mesesPendientes()
{
    $inicio = now()->subYears(1)->startOfYear(); // o fecha de inscripción
    $actual = now()->startOfMonth();

    $pagados = $this->pagos->pluck('mes_pago')->map(fn($f) => \Carbon\Carbon::parse($f)->format('Y-m'))->toArray();

    $meses = [];
    while ($inicio <= $actual) {
        $mesFormato = $inicio->format('Y-m');
        if (!in_array($mesFormato, $pagados)) {
            $meses[] = $mesFormato;
        }
        $inicio->addMonth();
    }

    return $meses;
}
}
