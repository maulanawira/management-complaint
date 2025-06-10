<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAdminEmployeeIdFromComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Pastikan kolom dan foreign key ada dulu baru dihapus
            if (Schema::hasColumn('complaints', 'admin_employee_id')) {
                // Lepas foreign key dulu
                $table->dropForeign(['admin_employee_id']);
                // Hapus kolomnya
                $table->dropColumn('admin_employee_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Tambah kolom kembali jika rollback
            if (!Schema::hasColumn('complaints', 'admin_employee_id')) {
                $table->unsignedBigInteger('admin_employee_id')->nullable()->after('feedback');
                $table->foreign('admin_employee_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }
}
