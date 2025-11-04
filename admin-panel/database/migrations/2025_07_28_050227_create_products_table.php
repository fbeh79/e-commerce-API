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
            $table->string('primary_image');
            $table->string('description');
            $table->string('category_id');
            $table->string('price');
            $table->string('sale_price');
            $table->integer('status');
            $table->string('quantity');
            $table->string('date_on_sale_from');
            $table->string('date_on_sale_to');
            $table->string('images');
            $table->timestamps();
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
