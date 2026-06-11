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
            $table->integer('required_points')->default(0)->after('unlock_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_categories', function (Blueprint $table) {
            $table->dropColumn('required_points');
        });
    }
};
