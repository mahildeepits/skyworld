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
        Schema::table('agent_categories', function (Blueprint $table) {
            $table->decimal('unlock_balance', 15, 2)->nullable()->change();
            $table->decimal('massive_order_rate', 10, 2)->nullable()->change();
            $table->decimal('community_bonus_rate', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_categories', function (Blueprint $table) {
            //
        });
    }
};
