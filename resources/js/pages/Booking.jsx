import React, { useState } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import MainLayout from '@/components/MainLayout';
import BookingTimeline from '@/components/BookingTimeline'; // Import komponen timeline

export default function Booking() {
  const [searchCode, setSearchCode] = useState('');
  const [bookingDetails, setBookingDetails] = useState(null);

  const handleTrackBooking = async () => {
    // Validasi input kosong
    if (!searchCode.trim()) {
      Swal.fire({
        icon: 'warning',
        title: 'Kode Booking Kosong',
        text: 'Silakan masukkan kode booking'
      });
      return;
    }

    try {
      console.log('Searching Ticket Code:', searchCode);

      // =========================================================================
      // UBAH process.env.NEXT_PUBLIC_API_BASE_URL menjadi process.env.VITE_API_BASE_URL
      // =========================================================================
      const API_BASE_URL = process.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000';

      const response = await axios.get(`${API_BASE_URL}/track-order/${searchCode}`);


      console.log('Booking Details:', response.data);
      setBookingDetails(response.data);
    } catch (error) {
      console.error('Tracking Error:', error.response?.data);

      Swal.fire({
        icon: 'error',
        title: 'Booking Tidak Ditemukan',
        text: error.response?.data?.message || 'Kode booking tidak valid'
      });
    }
  };

  // Render method untuk detail booking dan timeline
  const renderBookingStatus = () => {
    if (!bookingDetails) return null;

    return (
      <div className="bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 className="text-xl font-bold mb-4">Detail Booking</h2>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div>
            <p><strong>Kode Booking:</strong> {bookingDetails.ticket_code}</p>
            <p><strong>Nama:</strong> {bookingDetails.customer_name}</p>
            <p><strong>Venue:</strong> {bookingDetails.venue_name}</p>
          </div>
          <div>
            <p><strong>Tanggal:</strong> {bookingDetails.booking_date}</p>
            <p><strong>Jam:</strong> {bookingDetails.booking_time}</p>
            <p>
              <strong>Status:</strong>
              <span
                className={`ml-2 px-2 py-1 rounded text-white ${
                  bookingDetails.status_code === 1 ? 'bg-yellow-500' :
                  bookingDetails.status_code === 2 ? 'bg-green-500' :
                  bookingDetails.status_code === 3 ? 'bg-blue-500' :
                  bookingDetails.status_code === 4 ? 'bg-red-500' : 'bg-gray-500'
                }`}
              >
                {bookingDetails.status_label}
              </span>
            </p>
          </div>
        </div>

        {/* Tambahkan komponen BookingTimeline di sini */}
        <BookingTimeline currentStatusCode={bookingDetails.status_code} />
      </div>
    );
  };

  return (
    <MainLayout>
      <div className="container mx-auto px-4 py-8">
        <div className="max-w-xl mx-auto text-center">
          <h1 className="text-2xl font-bold mb-6">Lacak Status Booking</h1>

          <div className="flex mb-6">
            <input
              type="text"
              placeholder="Masukkan Kode Booking"
              value={searchCode}
              onChange={(e) => setSearchCode(e.target.value)}
              className="flex-1 border rounded-l px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
              maxLength={20}
            />
            <button
              onClick={handleTrackBooking}
              className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-r transition-colors duration-200"
            >
              Cari
            </button>
          </div>

          {renderBookingStatus()}
        </div>
      </div>
    </MainLayout>
  );
}