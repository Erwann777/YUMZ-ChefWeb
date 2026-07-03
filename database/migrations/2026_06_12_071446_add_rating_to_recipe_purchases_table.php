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
        Schema::table('recipe_purchases', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('amount_paid');
            $table->text('review')->nullable()->after('rating');
            $table->timestamp('rated_at')->nullable()->after('review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipe_purchases', function (Blueprint $table) {
            $table->dropColumn(['rating', 'review', 'rated_at']);
        });
    }
};
