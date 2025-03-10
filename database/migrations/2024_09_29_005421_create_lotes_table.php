<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    protected $table = 'lotes';
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->string('numero_lote', 50);
            $table->string('producto_cod');
            $table->float('cantidad');
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_vencimiento');
            $table->timestamps();

            $table->foreign('producto_cod')->references('codigo')->on('productos')->onDelete('cascade');
            $table->primary('numero_lote');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
