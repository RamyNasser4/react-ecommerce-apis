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
        Schema::table('users', function (Blueprint $table) {
            /* $table->string('address')->nullable();
            $table->string('phone_number')->nullable(); */
            $table->string('profile_img',1000)->default('https://salinaka-ecommerce.web.app/images/defaultAvatar.4e9edb2a624547982816014bf128fcd5.jpg')->change();
            /* $table->string('cover_img',1000)->default('https://firebasestorage.googleapis.com/v0/b/salinaka-ecommerce.appspot.com/o/banner%2FfOGDhbnQxHO2s0LYnsDxKHY2ZYH3?alt=media&token=4b7052cf-1dc7-4e7d-a587-17d77860082e'); */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
