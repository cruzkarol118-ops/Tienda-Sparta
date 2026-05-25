<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_variant_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_sku_id');
            $table->unsignedBigInteger('variant_option_id');
            $table->timestamps();
            
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');
            $table->foreign('variant_option_id')->references('id')->on('variant_options')->onDelete('cascade');
            
            $table->unique(['product_sku_id', 'variant_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sku_variant_options');
    }
};
