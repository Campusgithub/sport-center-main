import React, { useState, useEffect } from 'react';
import { usePage, Link } from '@inertiajs/react';
import MainLayout from '@/components/MainLayout';
import VenueCard from '@/components/VenueCard';
import Swal from 'sweetalert2';

export default function Home() {
  const { venues } = usePage().props;
  const [search, setSearch] = useState('');

  const filteredVenues = venues
    .filter((v) => v.name.toLowerCase().includes(search.toLowerCase()))
    .slice(0, 4); // 4 rekomendasi saja

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('payment') === 'success') {
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Pembayaran Berhasil!",
        showConfirmButton: false,
        timer: 1500
      });
    }
  }, []);

  return (
    <MainLayout>
      {/* HERO Section */}
      <section className="relative w-full h-[400px] md:h-[500px] mb-12">
        <img
          src="/storage/image/Sport Center.png"
          alt="Sport Center"
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-black bg-opacity-50 flex justify-end items-center px-6 md:px-20">
          <div className="text-white text-right max-w-md">
            <h1 className="text-3xl md:text-5xl font-bold mb-4">SPORT CENTER</h1>
            <p className="mb-6">Platform terbaik untuk reservasi lapangan olahraga secara online.</p>
            <Link
              href="/venue"
              className="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg"
            >
              Booking Sekarang
            </Link>
          </div>
        </div>
      </section>

      {/* MENGAPA MEMILIH KAMI */}
      <section className="text-center py-12 bg-gray-50">
        <h2 className="text-xl font-bold mb-6">Mengapa Memilih Kami?</h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
          {[
            { icon: '🕒', title: 'Booking Mudah', desc: 'Pemesanan cepat & konfirmasi instan' },
            { icon: '💰', title: 'Harga Terbaik', desc: 'Penawaran menarik setiap hari' },
            { icon: '✅', title: 'Kualitas Terjamin', desc: 'Fasilitas berkualitas & rutin dicek' },
          ].map((item, i) => (
            <div key={i} className="text-center">
              <div className="text-4xl mb-2">{item.icon}</div>
              <h3 className="font-semibold">{item.title}</h3>
              <p className="text-sm text-gray-600">{item.desc}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Rekomendasi */}
      <section className="p-6 mt-10">
        <div className="grid grid-cols-1 md:grid-cols-3 items-center mb-6">
          <div></div>
          <h2 className="text-xl font-bold text-center">Rekomendasi Lapangan</h2>
          <div className="text-right">
            <Link href="/venue" className="text-sm text-blue-600 hover:underline">
              Lihat Semua
            </Link>
          </div>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
          {filteredVenues.length > 0 ? (
            filteredVenues.map((venue) => (
              <VenueCard key={venue.id} venue={venue} />
            ))
          ) : (
            <p className="text-gray-500 col-span-full text-center">Lapangan tidak ditemukan.</p>
          )}
        </div>
      </section>
    </MainLayout>
  );
}
