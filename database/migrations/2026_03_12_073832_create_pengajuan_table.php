<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
    $table->id('id_pengajuan');
    $table->unsignedBigInteger('id_desa');
    $table->string('nama_domain',100);
    $table->enum('status_pengajuan',['ditinjau','draft','disetujui','perlu_perbaikan']);
    $table->text('catatan_umum')->nullable();
    $table->dateTime('tgl_pengajuan')->nullable();
    $table->dateTime('tgl_verifikasi')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};