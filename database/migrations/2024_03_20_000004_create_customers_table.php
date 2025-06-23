<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone_number', 20);
            $table->string('email', 100);
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
        Schema::dropIfExists('customers');
    }
}; 