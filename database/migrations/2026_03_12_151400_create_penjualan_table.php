<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_penjualan')->unique();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('tanggal_penjualan');
            $table->integer('total_jumlah')->default(0);
            $table->bigInteger('total_harga')->default(0);
            $table->bigInteger('jumlah_bayar')->default(0);
            $table->bigInteger('kembalian')->default(0);
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
