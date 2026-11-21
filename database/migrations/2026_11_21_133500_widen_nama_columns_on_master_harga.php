<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nama jalur seperti "ANAK PENDIDIK/TENAGA PENDIDIK BAITUL QUR'AN" > 30 char
        // menyebabkan Data too long saat simpan Master Harga.
        if (Schema::hasTable('master_harga')) {
            DB::statement('ALTER TABLE master_harga MODIFY nama_jalur VARCHAR(150) NOT NULL');
            DB::statement('ALTER TABLE master_harga MODIFY nama_gelombang VARCHAR(50) NOT NULL');
            DB::statement('ALTER TABLE master_harga MODIFY nama_fakultas VARCHAR(100) NOT NULL');
            DB::statement('ALTER TABLE master_harga MODIFY nama_prodi VARCHAR(100) NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('master_harga')) {
            DB::statement('ALTER TABLE master_harga MODIFY nama_jalur VARCHAR(30) NOT NULL');
            DB::statement('ALTER TABLE master_harga MODIFY nama_gelombang VARCHAR(2) NOT NULL');
            DB::statement('ALTER TABLE master_harga MODIFY nama_fakultas VARCHAR(30) NOT NULL');
            DB::statement('ALTER TABLE master_harga MODIFY nama_prodi VARCHAR(30) NOT NULL');
        }
    }
};
