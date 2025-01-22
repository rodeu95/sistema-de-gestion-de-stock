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
            $table->string('codigo');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('unidad');
            $table->decimal('precio_costo', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('iva');
            $table->decimal('utilidad');
            $table->float('stock')->default(0);
            $table->float('stock_minimo');
            $table->unsignedBigInteger('categoria_id');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            
            $table->foreign('categoria_id')->references('id')->on('categorias');
            
            $table->primary('codigo');
            
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
