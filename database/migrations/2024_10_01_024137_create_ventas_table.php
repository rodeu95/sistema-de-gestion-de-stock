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
            $table->unsignedBigInteger('producto_id'); // ID del producto vendido (relación con la tabla productos)
            $table->integer('cantidad'); // Cantidad vendida del producto
            $table->decimal('monto_total', 10, 2);
            $table->string('metodo_pago'); // Monto total de la venta
            $table->date('fecha_venta')->default(DB::raw('CURRENT_DATE')); // Fecha de la venta
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
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
