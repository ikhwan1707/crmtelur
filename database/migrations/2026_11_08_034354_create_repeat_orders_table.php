<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepeatOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeat_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('interval_hari')->nullable();
            $table->decimal('jumlah_rata2', 10, 2)->nullable();
            $table->date('last_order_date')->nullable();
            $table->date('next_order_date')->nullable();
            $table->enum('status', ['aktif', 'terlambat', 'berhenti'])->default('aktif');
            $table->timestamps();
            $table->unique(['customer_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repeat_orders');
    }
}