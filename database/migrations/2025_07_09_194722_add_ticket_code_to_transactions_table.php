<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketCodeToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Pastikan kolom belum ada sebelumnya
            if (!Schema::hasColumn('transactions', 'ticket_code')) {
                $table->string('ticket_code')->unique()->nullable()->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('ticket_code');
        });
    }
}
