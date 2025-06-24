<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mensualidads', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
    $table->string('mes'); // 'Enero', 'Febrero', etc.
    $table->integer('año');
    $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');
    $table->date('fecha_pago')->nullable();
    $table->decimal('monto', 8, 2)->default(0);
    $table->foreignId('recibo_id')->nullable()->constrained('recibos')->onDelete('set null');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensualidads');
    }
};
