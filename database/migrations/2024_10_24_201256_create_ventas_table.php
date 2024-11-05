<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto_total', 10, 2);
            $table->unsignedBigInteger('metodo_pago_id'); 
            $table->date('fecha_venta')->default(DB::raw('CURRENT_DATE')); // Fecha de la venta
            $table->timestamps();
            $table->foreign('metodo_pago_id')->references('id')->on('metodos_de_pago')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
