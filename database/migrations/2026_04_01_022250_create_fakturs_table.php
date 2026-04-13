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
        Schema::create('fakturs', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('id_pengajuan');

  $table->foreign('id_pengajuan')
          ->references('id_pengajuan')
          ->on('pengajuan')
          ->onDelete('cascade');


    $table->string('nama_desa');
    $table->string('nama_domain');
    $table->string('no_invoice');

    $table->integer('total');

    $table->enum('status', [
        'belum_bayar',
        'sudah_bayar',
        'kedaluarsa'
    ])->default('belum_bayar');
    $table->string('bukti_pembayaran_path')->nullable()->after('status');

    $table->timestamp('tanggal_konfirmasi')->nullable();
    $table->timestamp('expired_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fakturs');
    }
};
