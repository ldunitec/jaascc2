<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $fillable = ['nombre', 'correo', 'telefono', 'direccion', 'activo'];

    public function mensualidades()
    {
        return $this->hasMany(Mensualidad::class);
    }

    public function recibos()
    {
        return $this->hasMany(Recibo::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function mesesPendientes()
    {
        $inicio = now()->subYears(2)->startOfYear();
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
