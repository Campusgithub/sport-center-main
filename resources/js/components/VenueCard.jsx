import React from 'react';

function formatRupiah(price) {
  return `Rp ${Number(price).toLocaleString('id-ID')}`;
}

export default function VenueCard({ venue, onClick }) {
  return (
    <div
      onClick={() => onClick(venue)}
      className="cursor-pointer border rounded-lg overflow-hidden shadow-sm transform transition duration-300 hover:scale-105 hover:shadow-xl hover:-translate-y-1"
    >
      <img
        src={venue.image ? `/storage/${venue.image}` : '/images/default.jpg'}
        alt={venue.name}
        className="w-full h-40 object-cover"
      />
      <div className="p-4">
        <h3 className="text-lg font-semibold">{venue.name}</h3>
        <p className="text-sm text-gray-600 mt-1">{formatRupiah(venue.price)}</p>
      </div>
    </div>
  );
}
