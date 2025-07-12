import React, { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';
import MainLayout from '@/components/MainLayout';
import SearchBar from '@/components/SearchBar';
import VenueCard from '@/components/VenueCard';
import PopupVenueDetail from '@/components/PopupVenueDetail';
import Swal from 'sweetalert2';

export default function Venue() {
  const { venues = [] } = usePage().props;
  const [search, setSearch] = useState('');
  const [selectedVenue, setSelectedVenue] = useState(null);

  const filteredVenues = venues.filter((venue) =>
    venue.name.toLowerCase().includes(search.toLowerCase())
  );  console.log('venues:', venues);

  const handleBookingConfirm = () => {
    if (!selectedVenue) return;
    Inertia.visit('/booking', {
      method: 'get',
      data: {
        venue_id: selectedVenue.id,
        date: new Date().toISOString().split('T')[0],
        times: '08:00-09:00',
      },
    });
  };

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('payment') === 'success') {
      Swal.fire({
        icon: "success",
        title: "Pembayaran Berhasil!",
        showConfirmButton: false,
        timer: 1500
      });
      // Hapus query agar alert tidak muncul lagi saat reload
      window.history.replaceState({}, document.title, "/venue");
    }
  }, []);

  return (
    <MainLayout>
      <div className="px-6 py-8">
        <h1 className="text-2xl text-center font-bold mb-6">Daftar Lapangan</h1>

        <div className="mb-6 flex justify-center">
          <SearchBar search={search} setSearch={setSearch} onSearchClick={() => {}} />
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {filteredVenues.length > 0 ? (
            filteredVenues.map((venue) => (
              <VenueCard
                key={venue.id}
                venue={venue}
                onClick={() => {
                  console.log('Venue clicked:', venue); // cek log
                  setSelectedVenue(venue);
                }}
              />
            ))
          ) : (
            <p className="text-gray-500 col-span-full text-center">Lapangan tidak ditemukan.</p>
          )}
        </div>
      </div>

      {selectedVenue && (
        <PopupVenueDetail
          venue={selectedVenue}
          selectedDate={new Date().toISOString().split('T')[0]}
          selectedTimes={['08:00-09:00']} // dummy sementara
          onClose={() => setSelectedVenue(null)}
          onConfirm={handleBookingConfirm}
        />
      )}
    </MainLayout>
  );
};
