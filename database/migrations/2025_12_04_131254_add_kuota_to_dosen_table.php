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
        Schema::table('dosen', function (Blueprint $table) {
            $table->unsignedInteger('kuota_pembimbing_1')->default(10)->after('no_hp');
            $table->unsignedInteger('kuota_pembimbing_2')->default(15)->after('kuota_pembimbing_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn(['kuota_pembimbing_1', 'kuota_pembimbing_2']);
        });
    }
};
