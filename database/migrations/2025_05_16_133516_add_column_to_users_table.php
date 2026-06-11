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
            if(Schema::hasColumn('users', 'wallet_address')){
                $table->dropColumn('wallet_address');
            }
            if(!Schema::hasColumn('users','wallet_addresses')){
                $table->json('wallet_addresses')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users','wallet_addresses')){
                $table->dropColumn('wallet_addresses');
            }
        });
    }
};
