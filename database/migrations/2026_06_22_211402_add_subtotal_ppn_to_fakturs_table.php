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
    Schema::table('fakturs', function (Blueprint $table) {
        $table->decimal('subtotal', 15, 2)->nullable()->after('total');
        $table->decimal('ppn', 15, 2)->nullable()->after('subtotal');
    });
}

public function down()
{
    Schema::table('fakturs', function (Blueprint $table) {
        $table->dropColumn(['subtotal', 'ppn']);
    });
}
};
