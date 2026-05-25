<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'priority')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('priority')->default(0)->after('base_stock');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'priority')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }
};
