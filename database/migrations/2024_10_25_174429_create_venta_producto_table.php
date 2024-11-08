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
        Schema::create('venta_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id'); // ID de la venta
            $table->string('producto_cod'); // ID del producto
            $table->float('cantidad');
            $table->timestamps();

            $table->foreign('venta_id')->references('id')->on('ventas')->onDelete('cascade');
            $table->foreign('producto_cod')->references('codigo')->on('productos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_producto');
    }
};
