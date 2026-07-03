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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 15, 2); // Amount in user's own currency
            $table->enum('currency', ['IDR', 'SGD', 'MYR']);
            $table->string('reference_type')->nullable(); // recipe_purchase, service_order, initial_credit, topup
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('original_amount', 15, 2)->nullable(); // Original price in seller's currency
            $table->enum('original_currency', ['IDR', 'SGD', 'MYR'])->nullable();
            $table->decimal('exchange_rate', 15, 6)->nullable(); // Rate used for conversion
            $table->string('description');
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
