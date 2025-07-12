<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Migration ini hanya untuk tabel transactions, biarkan default.
    }

    public function down()
    {
        // Tidak perlu rollback apa-apa
    }
};
