<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // ID auto-incremental
            $table->string('name'); // Nombre del artículo
            $table->text('description'); // Descripción del artículo
            $table->string('image')->nullable(); // Imagen del artículo (opcional)
            $table->decimal('price', 8, 2); // Precio del artículo
            $table->decimal('discount', 5, 2)->default(0); // Descuento (porcentaje) del artículo
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
