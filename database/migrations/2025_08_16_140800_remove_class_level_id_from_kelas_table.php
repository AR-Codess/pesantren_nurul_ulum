<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Find and drop foreign key for class_level_id if exists
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();
        $fkName = null;
        $results = $conn->select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'kelas' AND COLUMN_NAME = 'class_level_id' AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$dbName]
        );
        if (!empty($results)) {
            $fkName = $results[0]->CONSTRAINT_NAME;
        }
        Schema::table('kelas', function (Blueprint $table) use ($fkName) {
            if ($fkName) {
                $table->dropForeign($fkName);
            }
            if (Schema::hasColumn('kelas', 'class_level_id')) {
                $table->dropColumn('class_level_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('class_level_id')->nullable()->constrained('class_level')->onDelete('cascade');
        });
    }
};
