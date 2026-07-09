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
        Schema::table('cooking_services', function (Blueprint $table) {
            $table->string('image_path')->nullable();
            $table->string('category')->default('indonesia');
            $table->boolean('is_halal')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cooking_services', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'category', 'is_halal']);
        });
    }
};
