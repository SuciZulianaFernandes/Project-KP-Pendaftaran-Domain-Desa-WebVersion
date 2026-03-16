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
        Schema::create('desa', function (Blueprint $table) {
    $table->id('id_desa');
    $table->unsignedBigInteger('id_user');

    $table->string('nama_desa',100);
    $table->string('nama_kepala_desa',100);
    $table->string('nip_kepala_desa',30);
    $table->text('alamat');
    $table->string('id_prov',10);
    $table->string('id_kab',10);

    $table->timestamps();

    $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
