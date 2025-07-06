<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Change from a single periode_pembayaran date column to
     * separate periode_bulan and periode_tahun integer columns
     */
    public function up(): void
    {
        // 1. Add the new columns as nullable initially
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->integer('periode_bulan')->nullable()->after('periode_pembayaran');
            $table->integer('periode_tahun')->nullable()->after('periode_bulan');
        });

        // 2. Migrate data from periode_pembayaran to the new columns
        $pembayarans = DB::table('pembayaran')->get();
        
        foreach ($pembayarans as $pembayaran) {
            if ($pembayaran->periode_pembayaran) {
                $date = new \DateTime($pembayaran->periode_pembayaran);
                
                DB::table('pembayaran')
                    ->where('id', $pembayaran->id)
                    ->update([
                        'periode_bulan' => (int)$date->format('m'), // Extract month as integer (1-12)
                        'periode_tahun' => (int)$date->format('Y'), // Extract year as integer
                    ]);
            }
        }

        // 3. Make the new columns required after data migration
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->integer('periode_bulan')->nullable(false)->change();
            $table->integer('periode_tahun')->nullable(false)->change();
        });

        // 4. Drop the old periode_pembayaran column
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('periode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add back the periode_pembayaran column as nullable
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->date('periode_pembayaran')->nullable()->after('total_tagihan');
        });

        // 2. Migrate data back to periode_pembayaran from periode_bulan and periode_tahun
        $pembayarans = DB::table('pembayaran')->get();
        
        foreach ($pembayarans as $pembayaran) {
            if (isset($pembayaran->periode_bulan) && isset($pembayaran->periode_tahun)) {
                // Create date string in format YYYY-MM-DD using first day of month
                $dateString = sprintf(
                    '%04d-%02d-01',
                    $pembayaran->periode_tahun,
                    $pembayaran->periode_bulan
                );
                
                DB::table('pembayaran')
                    ->where('id', $pembayaran->id)
                    ->update(['periode_pembayaran' => $dateString]);
            }
        }

        // 3. Make periode_pembayaran required after data migration
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->date('periode_pembayaran')->nullable(false)->change();
        });

        // 4. Drop the new columns
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['periode_bulan', 'periode_tahun']);
        });
    }
};
