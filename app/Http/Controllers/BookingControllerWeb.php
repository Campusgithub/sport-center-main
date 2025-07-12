<?php


namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingControllerWeb extends Controller
{
public function sendWa($id)
{
    $transaction = Transaction::with('customer', 'venue')->findOrFail($id);

    // Format nomor WA dari relasi customer
    $phone = preg_replace('/[^0-9]/', '', $transaction->customer->phone_number);
    $waNumber = '62' . ltrim($phone, '0');

    // Format tanggal & jam
    $tanggal    = \Carbon\Carbon::parse($transaction->start_time)->format('d F Y');
    $jamMulai   = \Carbon\Carbon::parse($transaction->start_time)->format('H:i');
    $jamSelesai = \Carbon\Carbon::parse($transaction->end_time)->format('H:i');

    // Gunakan dash biasa agar tidak error di URL
    $jam = "{$jamMulai}-{$jamSelesai}";

    // Buat pesan WhatsApp
    $msg = <<<EOD
Halo {$transaction->customer->name}! 👋

📅 Tanggal: {$tanggal}
🏟 Lapangan: {$transaction->venue->name}
🕒 Jam: {$jam}
💰 Status: {$transaction->status_transaksi}

🧾 Struk Anda:
{url("/admin/transactions/{$transaction->id}/print")}

Terima kasih 🙏
EOD;

    // Redirect ke wa.me
    return redirect("https://wa.me/{$waNumber}?text=" . urlencode($msg));
}



    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'date' => 'required|date',
            'time_slots' => 'required|array|min:1',
            'total_price' => 'required|numeric',
        ]);

        $bookingCode = 'BOOK-' . strtoupper(substr(uniqid(), 0, 8));

        Transaction::create([
            'venue_id' => $data['venue_id'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'booking_date' => $data['date'],
            'booking_time' => implode(', ', $data['time_slots']),
            'total_price' => $data['total_price'],
            'status' => 'pending',
            'booking_code' => $bookingCode,
        ]);

        return view('booking.checkout', [
    'bookingCode' => $bookingCode,
]);

    }
}
