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
        Schema::table('rewards', function (Blueprint $table) {
            if(!Schema::hasColumn('rewards','days')){
                $table->integer('days')->nullable();
            }
            if(!Schema::hasColumn('rewards','amount')){
                $table->integer('amount')->nullable();
            }
            if(!Schema::hasColumn('rewards','description')){
                $table->json('description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            if(Schema::hasColumn('rewards','days')){
                $table->dropColumn('days');
            }
            if(Schema::hasColumn('rewards','amount')){
                $table->dropColumn('amount');
            }
            if(Schema::hasColumn('rewards','description')){
                $table->dropColumn('description');
            }
        });
    }
};
