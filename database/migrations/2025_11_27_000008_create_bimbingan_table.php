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
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topik_id')->constrained('topik_skripsi')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosen')->cascadeOnDelete();
            $table->enum('jenis', ['proposal', 'skripsi']);
            $table->integer('bimbingan_ke')->default(1);
            $table->text('pokok_bimbingan');
            $table->string('file_bimbingan', 255)->nullable();
            $table->text('pesan_mahasiswa')->nullable();
            $table->text('pesan_dosen')->nullable();
            $table->string('file_revisi', 255)->nullable();
            $table->enum('status', ['menunggu', 'direvisi', 'disetujui'])->default('menunggu');
            $table->timestamp('tanggal_bimbingan')->useCurrent();
            $table->timestamp('tanggal_respon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan');
    }
};
