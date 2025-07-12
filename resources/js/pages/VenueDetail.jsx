import React, { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';
import axios from '../config/axios';

export default function VenueDetail() {
  const { venue } = usePage().props;
  const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split('T')[0]);
  const [availableSlots, setAvailableSlots] = useState([]);
  const [selectedTimes, setSelectedTimes] = useState([]);
  
  // State untuk form booking
  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');

  const timeSlots = [
    '07:00-08:00', '08:00-09:00', '09:00-10:00', '10:00-11:00',
    '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00',
    '15:00-16:00', '16:00-17:00', '17:00-18:00', '18:00-19:00',
    '19:00-20:00', '20:00-21:00', '21:00-22:00', '22:00-23:00'
  ];

  // Fetch slot yang tersedia
  useEffect(() => {
    const fetchAvailableSlots = async () => {
      try {
        const response = await axios.get('/api/bookings/check-slots', {
          params: {
            venue_id: venue.id,
            date: selectedDate
          }
        });
        setAvailableSlots(response.data.slots);
        setSelectedTimes([]); // Reset selected times
      } catch (error) {
        console.error('Error fetching slots:', error);
        Swal.fire({
          icon: 'error',
          title: 'Gagal Memuat Slot',
          text: 'Tidak dapat mengambil informasi slot venue'
        });
      }
    };

    fetchAvailableSlots();
  }, [venue.id, selectedDate]);

  const toggleTimeSlot = (time) => {
    setSelectedTimes((prev) =>
      prev.includes(time)
        ? prev.filter((t) => t !== time)
        : [...prev, time]
    );
  };

  const calculateTotal = () => {
    return (venue?.price || 0) * selectedTimes.length;
  };

  const handleBooking = async () => {
    // Validasi input
    if (!name.trim()) {
      Swal.fire({
        icon: 'warning',
        title: 'Nama Kosong',
        text: 'Silakan masukkan nama Anda'
      });
      return;
    }

    if (!phone.trim()) {
      Swal.fire({
        icon: 'warning',
        title: 'Nomor Telepon Kosong',
        text: 'Silakan masukkan nomor telepon Anda'
      });
      return;
    }

    // Kirim booking sementara
    try {
      const bookingPromises = selectedTimes.map(time => 
        axios.post('/api/bookings/temporary', {
        venue_id: venue.id,
        date: selectedDate,
          time: time,
          name: name,
          phone: phone
        })
      );

      const results = await Promise.all(bookingPromises);

      // Tampilkan konfirmasi booking
      Swal.fire({
        icon: 'success',
        title: 'Booking Berhasil',
        text: `Booking untuk ${selectedTimes.length} slot berhasil dibuat`,
        confirmButtonText: 'OK'
      });

      // Reset form
      setName('');
      setPhone('');
      setSelectedTimes([]);
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Booking Gagal',
        text: error.response?.data?.message || 'Terjadi kesalahan saat booking'
      });
    }
  };

  return (
    <div className="max-w-2xl mx-auto px-4 py-8">
      <img
        src={venue.image ? `/storage/${venue.image}` : '/images/default.jpg'}
        alt={venue.name}
        className="w-full h-64 object-cover rounded-lg shadow mb-6"
      />

      <h1 className="text-2xl font-bold mb-6">{venue.name}</h1>

      <div className="space-y-4 mb-6">
        <InfoRow label="Tipe" value={venue.type || '-'} />
        <InfoRow label="Harga" value={`Rp. ${venue.price?.toLocaleString('id-ID')}/jam`} />
        <InfoRow label="Lokasi" value={venue.location || '-'} />
        <div className="flex items-center">
          <span className="w-24">Tanggal</span>
          <span className="px-2">:</span>
          <input
            type="date"
            value={selectedDate}
            onChange={(e) => {
              setSelectedDate(e.target.value);
              setSelectedTimes([]);
            }}
            min={new Date().toISOString().split('T')[0]}
            className="border rounded px-2 py-1"
          />
        </div>
      </div>

      <div className="mb-8">
        <h2 className="font-semibold mb-4">Pilih Jam</h2>
        <div className="grid grid-cols-4 gap-2">
          {availableSlots.map((slot) => (
              <button
              key={slot.start_time}
              onClick={() => slot.is_available && toggleTimeSlot(slot.start_time)}
              disabled={!slot.is_available}
                className={`py-2 px-3 text-sm rounded-lg transition-colors ${
                !slot.is_available
                    ? 'bg-red-500 text-white cursor-not-allowed'
                  : selectedTimes.includes(slot.start_time)
                      ? 'bg-green-500 text-white'
                      : 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                }`}
              >
              {`${slot.start_time}-${slot.end_time}`}
              </button>
          ))}
        </div>
      </div>

      <div className="border-t pt-6">
        <div className="flex justify-between items-center mb-6">
          <span className="font-semibold">Total Harga</span>
          <span className="text-xl font-bold">
            Rp. {calculateTotal().toLocaleString('id-ID')}
          </span>
        </div>

        {/* Form Booking */}
        {selectedTimes.length > 0 && (
          <div className="mb-6 space-y-4">
            <input
              type="text"
              placeholder="Nama Lengkap"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full p-2 border rounded"
            />
            <input
              type="tel"
              placeholder="Nomor Telepon"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              className="w-full p-2 border rounded"
            />
          </div>
        )}

        <button
          onClick={handleBooking}
          disabled={selectedTimes.length === 0 || !name.trim() || !phone.trim()}
          className={`w-full py-3 rounded-lg transition-colors ${
            selectedTimes.length > 0 && name.trim() && phone.trim()
              ? 'bg-green-500 hover:bg-green-600 text-white'
              : 'bg-gray-300 text-gray-500 cursor-not-allowed'
          }`}
        >
          Pesan Sekarang
        </button>
      </div>
    </div>
  );
}

const InfoRow = ({ label, value }) => (
  <div className="flex">
    <span className="w-24">{label}</span>
    <span className="px-2">:</span>
    <span>{value}</span>
  </div>
);
