<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_level', function (Blueprint $table) {
            $table->bigInteger('spp_beasiswa')->nullable()->after('spp')->comment('Nominal SPP untuk siswa penerima beasiswa');
        });
    }

    public function down()
    {
        Schema::table('class_level', function (Blueprint $table) {
            $table->dropColumn('spp_beasiswa');
        });
    }
};
