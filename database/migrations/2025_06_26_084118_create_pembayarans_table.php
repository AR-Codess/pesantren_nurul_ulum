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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_tagihan');
            $table->date('periode_pembayaran');
            $table->string('status', 50); // 'Belum Bayar', 'Menunggu Pembayaran', 'Belum Lunas', 'Lunas'
            $table->boolean('is_cicilan')->default(false);
            $table->foreignId('admin_id_pembuat')->constrained('admin')->onDelete('cascade');
            $table->string('midtrans_order_id')->nullable(); // Untuk integrasi Midtrans
            $table->string('deskripsi')->nullable(); // Deskripsi pembayaran
            $table->string('jenis_pembayaran')->nullable(); // SPP, Beasiswa, Dll
            $table->timestamps();
        });

        // Create detail_pembayaran table
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayaran')->onDelete('cascade');
            $table->integer('jumlah_dibayar');
            $table->dateTime('tanggal_bayar');
            $table->string('metode_pembayaran', 50);
            $table->string('bukti_pembayaran')->nullable();
            $table->foreignId('admin_id_pencatat')->constrained('admin')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayaran');
        Schema::dropIfExists('pembayaran');
    }
};
