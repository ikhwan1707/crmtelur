<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTglJatuhTempoToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // nullable karena pesanan tunai tidak butuh jatuh tempo
            $table->date('tgl_jatuh_tempo')->nullable()->after('metode_bayar');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tgl_jatuh_tempo');
        });
    }
}