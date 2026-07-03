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
            $table->string('image_path')->nullable()->after('description');
            $table->string('category')->default('indonesia')->after('image_path');
            $table->boolean('is_halal')->default(true)->after('category');
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
