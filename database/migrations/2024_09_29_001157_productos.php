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
        Schema::create('productos', function (Blueprint $table){
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('unidad');
            $table->string('numero_lote', 50); // Cambia el nombre si es necesario
            $table->date('fchVto');
            $table->decimal('precio_costo', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('iva');
            $table->decimal('utilidad');
            $table->integer('stock');
            $table->integer('total_vendido')->default(0);
            $table->unsignedBigInteger('categoria_id');
            $table->timestamps();
            
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('numero_lote')->references('numero_lote')->on('lotes')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
