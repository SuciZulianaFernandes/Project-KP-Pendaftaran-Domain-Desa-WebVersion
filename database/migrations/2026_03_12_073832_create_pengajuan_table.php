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

    $table->string('nama_desa')->nullable();
        $table->string('telepon', 50)->nullable();
        $table->string('faksimili', 50)->nullable();
        $table->text('alamat')->nullable();
        $table->string('provinsi')->nullable();
        $table->string('kota_kabupaten')->nullable();
        $table->string('kecamatan')->nullable();
        $table->string('desa_kelurahan')->nullable();
        $table->string('kode_pos', 10)->nullable();
        
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};