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
        Schema::table('tasks', function (Blueprint $table) {
            if(!Schema::hasColumn('tasks', 'order_date')){
                $table->date('order_date')->nullable();
            }
            if(!Schema::hasColumn('tasks', 'order_number')){
                $table->string('order_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if(Schema::hasColumn('tasks', 'order_date')){
                $table->dropColumn('order_date');
            }
            if(Schema::hasColumn('tasks', 'order_number')){
                $table->dropColumn('order_number');
            }
        });
    }
};
