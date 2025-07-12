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
        // Sudah ada kolom order_id, tidak perlu apa-apa
}

    /**
     * Reverse the migrations.
     */
public function down()
{
        // Tidak perlu rollback apa-apa
}
};
