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
            $table->string('codigo');
            $table->string('nombre');
            $table->text('descripcion')->nullable()->change;
            $table->date('fchVto');
            $table->decimal('precio', 10,2);
            $table->integer('stock');
            $table->integer('total_vendido')->default(0);
            $table->unsignedBigInteger('categoria_id');
            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->timestamps();
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
