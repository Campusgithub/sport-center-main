<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Insert dengan field minimum yang required
    $result1 = DB::insert('INSERT INTO transactions (customer_id, venue_id, start_time, end_time, status_transaksi, ticket_code, ticket_url, is_ticket_sent, CompanyCode, Status, isDeleted, CreatedBy, CreatedDate, LastUpdatedBy, LastUpdatedDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
        1,
        1,
        '2025-07-13 07:00:00',
        '2025-07-13 08:00:00',
        'Lunas',
        'TICKET-' . time(),
        '',
        0,
        '',
        0,
        0,
        'system',
        now(),
        'system',
        now()
    ]);

    $result2 = DB::insert('INSERT INTO transactions (customer_id, venue_id, start_time, end_time, status_transaksi, ticket_code, ticket_url, is_ticket_sent, CompanyCode, Status, isDeleted, CreatedBy, CreatedDate, LastUpdatedBy, LastUpdatedDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
        1,
        1,
        '2025-07-13 08:00:00',
        '2025-07-13 09:00:00',
        'Lunas',
        'TICKET-' . (time()+1),
        '',
        0,
        '',
        0,
        0,
        'system',
        now(),
        'system',
        now()
    ]);

    echo "✅ Test data berhasil dibuat!\n";
    echo "- Booking 1: 07:00-08:00 untuk venue_id=1 tanggal 2025-07-13\n";
    echo "- Booking 2: 08:00-09:00 untuk venue_id=1 tanggal 2025-07-13\n";
    echo "\nSekarang jam 07:00 dan 08:00 seharusnya MERAH di frontend!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
