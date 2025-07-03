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
        Schema::create('class_level', function (Blueprint $table) {
            $table->id();
            $table->string('level')->notNull();
            $table->bigInteger('spp')->notNull();
            $table->bigInteger('spp_beasiswa')->nullable()->comment('Nominal SPP untuk siswa penerima beasiswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_level');
    }
};
