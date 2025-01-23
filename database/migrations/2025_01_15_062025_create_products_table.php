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
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description');
                $table->decimal('price', 10, 2); // Cambié a decimal para valores numéricos
                $table->integer('stock'); // Cambié a integer para cantidades
                $table->unsignedBigInteger('category_id'); // Llave foránea a 'categories'
                $table->string('status');
                $table->timestamps();

                // Definir la llave foránea
                $table->foreign('category_id')
                      ->references('id')
                      ->on('categories')
                      ->onDelete('cascade'); // Opcional: eliminar productos si la categoría se elimina
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
