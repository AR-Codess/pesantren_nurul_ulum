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
        // Create absensi_user pivot table
        Schema::create('absensi_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absensi_id')->constrained('absensi')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status', 20); // e.g., 'hadir', 'izin', 'sakit', 'alpha'
            $table->timestamps();
            
            // Create a unique constraint to prevent duplicate entries
            $table->unique(['absensi_id', 'user_id']);
        });

        // Create absensi_guru pivot table
        Schema::create('absensi_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absensi_id')->constrained('absensi')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->timestamps();
            
            // Create a unique constraint to prevent duplicate entries
            $table->unique(['absensi_id', 'guru_id']);
        });

        // Modify the existing absensi table to remove direct relationships
        Schema::table('absensi', function (Blueprint $table) {
            // First remove the foreign key constraints
            $table->dropForeign(['guru_id']);
            
            // Then drop the columns
            $table->dropColumn('guru_id');
            $table->dropColumn('status'); // Status will now be in the pivot table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the columns to absensi table
        Schema::table('absensi', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('guru')->onDelete('cascade');
            $table->string('status', 20)->nullable(); // Add status column back
        });

        // Drop the pivot tables
        Schema::dropIfExists('absensi_guru');
        Schema::dropIfExists('absensi_user');
    }
};