<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kota_id');
            $table->string('nama_kecamatan');
            $table->timestamps();
            
            $table->foreign('kota_id')->references('id')->on('kotas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
