<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('aktivasi', function (Blueprint $table) {
        $table->id('id_aktivasi');
        $table->unsignedBigInteger('id_pengajuan');
        $table->enum('status_akt', ['nonaktif', 'aktif', 'kadaluarsa'])->default('nonaktif');
        $table->dateTime('tgl_aktivasi')->nullable();
        $table->timestamps();

        $table->foreign('id_pengajuan')->references('id_pengajuan')->on('pengajuan')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivasi');
    }
};
