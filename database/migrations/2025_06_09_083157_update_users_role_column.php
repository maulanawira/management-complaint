<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Langkah 1: Update kolom role untuk menambahkan 'karyawan'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'karyawan', 'admin', 'supervisor'])->change();
        });

        // Langkah 2: Update semua data yang memiliki role 'customer' menjadi 'karyawan'
        DB::table('users')
            ->where('role', 'customer')
            ->update(['role' => 'karyawan']);

        // Langkah 3: Hapus 'customer' dari enum (opsional)
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['karyawan', 'admin', 'supervisor'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: kembalikan ke kondisi semula
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'karyawan', 'admin', 'supervisor'])->change();
        });

        DB::table('users')
            ->where('role', 'karyawan')
            ->update(['role' => 'customer']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'admin', 'supervisor'])->change();
        });
    }
};