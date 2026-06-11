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
        Schema::create('unified_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 16, 4)->default(0);
            $table->enum('transaction_type', ['Credit', 'Debit']);
            $table->string('category')->nullable(); // e.g. Deposit, Withdrawal, Level_Income, Direct_Income
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->string('tx_hash')->nullable();
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->default('Completed');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unified_transactions');
    }
};
