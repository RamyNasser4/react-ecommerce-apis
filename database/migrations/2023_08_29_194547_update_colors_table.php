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
        Schema::table('color', function (Blueprint $table) {
        });
        Schema::rename('color','colors');
        Schema::table('product_colors', function (Blueprint $table) {
        });
        Schema::rename('product_colors','color_product');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
