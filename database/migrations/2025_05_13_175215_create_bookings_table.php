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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Opsional untuk booking tanpa login
            $table->date('date');
            $table->string('time');
            $table->integer('status')->default(1);
            $table->decimal('price', 10, 2);
            $table->string('customer_name')->nullable(); // Tambahkan nama customer
            $table->string('customer_phone')->nullable(); // Tambahkan nomor telepon
            $table->timestamps();

            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
