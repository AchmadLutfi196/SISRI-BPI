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
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            $table->string('file_dokumen')->nullable()->after('catatan_koordinator');
            $table->string('file_dokumen_original_name')->nullable()->after('file_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_sidang', function (Blueprint $table) {
            $table->dropColumn(['file_dokumen', 'file_dokumen_original_name']);
        });
    }
};
