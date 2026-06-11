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
            if(Schema::hasColumn('agent_categories', 'community_bonus_rate')){
                $table->decimal('community_bonus_rate', 10, 2)->change();
            }
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
