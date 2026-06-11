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
        Schema::table('unified_transactions', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Completed', 'Failed', 'Rejected'])->default('Completed')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unified_transactions', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->default('Completed')->change();
        });
    }
};
