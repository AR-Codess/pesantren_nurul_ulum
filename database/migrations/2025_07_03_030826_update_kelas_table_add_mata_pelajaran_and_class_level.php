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
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('mata_pelajaran')->after('nama_kelas');
            $table->foreignId('class_level_id')->after('mata_pelajaran')->constrained('class_level')->onDelete('cascade');
            $table->dropColumn('nama_kelas'); // Removing old column as per new schema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('nama_kelas', 100)->after('id'); // Restore original column
            $table->dropForeign(['class_level_id']);
            $table->dropColumn(['mata_pelajaran', 'class_level_id']);
        });
    }
};
