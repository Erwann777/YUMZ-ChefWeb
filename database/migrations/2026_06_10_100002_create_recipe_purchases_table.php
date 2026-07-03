<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipe_id')->constrained('recipes')->cascadeOnDelete();
            $table->decimal('amount_paid', 10, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['customer_id', 'recipe_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_purchases');
    }
};
