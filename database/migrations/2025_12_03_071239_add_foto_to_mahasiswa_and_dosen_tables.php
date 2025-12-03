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
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('no_hp');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
