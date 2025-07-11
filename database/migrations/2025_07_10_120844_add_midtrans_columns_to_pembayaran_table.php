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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Cek jika kolom belum ada, lalu tambahkan
            if (!Schema::hasColumn('pembayaran', 'deskripsi')) {
                $table->string('deskripsi')->after('status')->nullable();
            }
            if (!Schema::hasColumn('pembayaran', 'jenis_pembayaran')) {
                $table->string('jenis_pembayaran')->after('deskripsi')->default('SPP');
            }
            if (!Schema::hasColumn('pembayaran', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->after('jenis_pembayaran')->nullable()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Fungsi down hanya akan menghapus kolom yang kita tambahkan
            $table->dropColumn(['deskripsi', 'jenis_pembayaran', 'midtrans_order_id']);
        });
    }
};