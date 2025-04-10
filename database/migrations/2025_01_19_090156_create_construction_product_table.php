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
        Schema::create('construction_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('construction_id');
            $table->unsignedBigInteger('product_id');
            $table->string('color');
            $table->string('quantity');
            $table->string('warranty');
            $table->timestamp('startDate');
            $table->timestamp('endDate');
            $table->string('status');
            $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_product');
    }
};
