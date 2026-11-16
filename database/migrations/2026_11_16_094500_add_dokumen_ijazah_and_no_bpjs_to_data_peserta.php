<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_peserta', function (Blueprint $table) {
            if (!Schema::hasColumn('data_peserta', 'dokumen_ijazah')) {
                $table->string('dokumen_ijazah', 255)->nullable()->after('dokumen_akte_kelahiran');
            }
            if (!Schema::hasColumn('data_peserta', 'no_bpjs')) {
                $table->string('no_bpjs', 30)->nullable()->after('no_kk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_peserta', function (Blueprint $table) {
            if (Schema::hasColumn('data_peserta', 'dokumen_ijazah')) {
                $table->dropColumn('dokumen_ijazah');
            }
            if (Schema::hasColumn('data_peserta', 'no_bpjs')) {
                $table->dropColumn('no_bpjs');
            }
        });
    }
};
