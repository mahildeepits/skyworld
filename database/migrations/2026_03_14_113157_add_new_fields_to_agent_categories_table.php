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
            $table->integer('team_a')->default(0);
            $table->integer('team_b_c')->default(0);
            $table->decimal('team_a_profit', 10, 2)->default(0);
            $table->decimal('team_b_profit', 10, 2)->default(0);
            $table->decimal('team_c_profit', 10, 2)->default(0);
            $table->decimal('level_upgrade_income', 10, 2)->default(0);
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
