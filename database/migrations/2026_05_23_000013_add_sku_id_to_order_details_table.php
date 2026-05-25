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
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'product_sku_id')) {
                $table->unsignedBigInteger('product_sku_id')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('order_details', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('product_sku_id');
            }
        });

        // Agregar foreign keys solo si las columnas existen y no tienen foreign keys
        if (Schema::hasColumn('order_details', 'product_sku_id')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('set null');
            });
        }
        
        if (Schema::hasColumn('order_details', 'product_id')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['product_sku_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_sku_id', 'product_id']);
        });
    }
};
