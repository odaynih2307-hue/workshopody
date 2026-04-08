<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kotas', function (Blueprint $table) {
            // Tambah kolom provinsi_id jika belum ada
            if (!Schema::hasColumn('kotas', 'provinsi_id')) {
                $table->unsignedBigInteger('provinsi_id')->nullable()->after('id');
                $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kotas', function (Blueprint $table) {
            if (Schema::hasColumn('kotas', 'provinsi_id')) {
                $table->dropForeign(['provinsi_id']);
                $table->dropColumn('provinsi_id');
            }
        });
    }
};
