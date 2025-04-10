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
        Schema::table('post_catalogue_translate', function (Blueprint $table) {
            Schema::rename('post_catalogue_translate', 'post_catalogue_language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_catalogue_translate', function (Blueprint $table) {
            Schema::rename('post_catalogue_translate', 'post_catalogue_language');
        });
    }
};
