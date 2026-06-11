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
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users','wallet_pin')){
                return $table->string('wallet_pin')->nullable();
            }
            if(!Schema::hasColumn('users','is_locked')){
                return $table->timestamp('is_locked')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users','wallet_pin')){
                $table->dropColumn('wallet_pin');
            }
            if(Schema::hasColumn('users','is_locked')){
                return $table->dropColumn('is_locked');
            }
        });
    }
};
