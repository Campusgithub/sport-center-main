<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained();
            $table->string('payment_code', 50);
            $table->string('payment_type', 50);
            $table->string('payment_status', 20);
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
        Schema::dropIfExists('payments');
    }
}; 