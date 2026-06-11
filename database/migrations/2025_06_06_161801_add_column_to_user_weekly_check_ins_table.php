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
        Schema::table('user_weekly_check_ins', function (Blueprint $table) {
            if(!Schema::hasColumn('user_weekly_check_ins', 'count')){
                $table->integer('count')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_weekly_check_ins', function (Blueprint $table) {
            if(Schema::hasColumn('user_weekly_check_ins', 'count')){
                $table->dropColumn('count');
            }
        });
    }
};
