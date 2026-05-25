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
        // Verificar si la columna slug ya existe
        if (!Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
                $table->unique('slug');
            });

            // Generar slugs para categorías existentes
            DB::table('categories')->whereNull('slug')->update([
                'slug' => DB::raw("LOWER(REPLACE(REPLACE(REPLACE(name, ' ', '-'), 'ñ', 'n'), 'áéíóú', 'aeiou'))")
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_slug_unique');
            $table->dropColumn('slug');
        });
    }
};
