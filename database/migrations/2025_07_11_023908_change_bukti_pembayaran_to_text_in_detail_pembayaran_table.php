<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pembayaran', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi TEXT
            $table->text('bukti_pembayaran')->change();
        });
    }

    public function down(): void
    {
        Schema::table('detail_pembayaran', function (Blueprint $table) {
            // Opsi untuk mengembalikan jika diperlukan
            $table->string('bukti_pembayaran')->change();
        });
    }
};