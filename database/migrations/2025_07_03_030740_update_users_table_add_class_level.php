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
            $table->foreignId('class_level_id')
                  ->after('nama_santri')
                  ->nullable() 
                  ->constrained('class_level')
                  ->onDelete('set null');
                  
            $table->boolean('is_beasiswa')->default(false)->after('spp_bulanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['class_level_id']);
            $table->dropColumn(['class_level_id', 'is_beasiswa']);
        });
    }
};
