<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('dokumen_persyaratan', function (Blueprint $table) {

        $table->id('id_dokumen');

        $table->unsignedBigInteger('id_pengajuan');

        $table->enum('jenis_dokumen', [
            'surat_permohonan',
            'perda_pembentukan_desa',
            'surat_kuasa',
            'surat_penunjukan_pejabat',
            'ktp_asn_pejabat'
        ]);

        $table->string('nama_file');
        $table->string('path_file');

        $table->timestamps();

        $table->foreign('id_pengajuan')
            ->references('id_pengajuan')
            ->on('pengajuan')
            ->onDelete('cascade');

    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_persyaratan');
    }
};
