<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooker_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooking_services');
    }
};
