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
        Schema::table('penguji_sidang', function (Blueprint $table) {
            $table->text('catatan_revisi')->nullable()->after('tanggal_ttd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penguji_sidang', function (Blueprint $table) {
            $table->dropColumn('catatan_revisi');
        });
    }
};
