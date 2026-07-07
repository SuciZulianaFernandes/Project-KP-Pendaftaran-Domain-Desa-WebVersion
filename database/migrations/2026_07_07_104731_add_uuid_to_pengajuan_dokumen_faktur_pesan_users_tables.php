<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    protected array $tables = ['pengajuan', 'dokumen_persyaratan', 'fakturs', 'pesan', 'users'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->uuid('uuid')->nullable()->unique();
            });
        }

        foreach ($this->tables as $table) {
            DB::table($table)->whereNull('uuid')->orderBy(
                $this->primaryKeyOf($table)
            )->each(function ($row) use ($table) {
                DB::table($table)
                    ->where($this->primaryKeyOf($table), $row->{$this->primaryKeyOf($table)})
                    ->update(['uuid' => (string) Str::uuid()]);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('uuid');
            });
        }
    }

    protected function primaryKeyOf(string $table): string
    {
        return match ($table) {
            'pengajuan' => 'id_pengajuan',
            'dokumen_persyaratan' => 'id_dokumen',
            'users' => 'id_user',
            default => 'id',
        };
    }
};
