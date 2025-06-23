<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('venue_id')->constrained();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status_transaksi', 20);
            $table->string('ticket_code', 100);
            $table->string('ticket_url', 255);
            $table->tinyInteger('is_ticket_sent')->default(0);
            $table->string('CompanyCode', 20);
            $table->tinyInteger('Status')->default(1);
            $table->tinyInteger('isDeleted')->default(0);
            $table->string('CreatedBy', 32);
            $table->dateTime('CreatedDate');
            $table->string('LastUpdatedBy', 32)->nullable();
            $table->dateTime('LastUpdatedDate')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 