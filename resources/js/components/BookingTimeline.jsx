// components/BookingTimeline.js
import React from 'react';
import './BookingTimeline.css'; // Kita akan membuat file CSS ini

// Definisikan semua kemungkinan status beserta kode dan labelnya
// Urutan di sini penting, karena akan menentukan urutan di timeline
const allPossibleStatuses = [
  { code: 1, label: "Menunggu Pembayaran" },
  { code: 2, label: "Dikonfirmasi" },
  { code: 3, label: "Selesai" },
  { code: 4, label: "Dibatalkan" } // Status pembatalan, mungkin tidak selalu di timeline linear
];

export default function BookingTimeline({ currentStatusCode }) {
  // Filter status yang relevan untuk timeline progres, misal tidak termasuk 'Dibatalkan'
  const relevantStatuses = allPossibleStatuses.filter(s => s.code !== 4); 
  const currentIndex = relevantStatuses.findIndex(status => status.code === currentStatusCode);

  // Jika statusnya adalah 'Dibatalkan', kita bisa menampilkannya secara terpisah
  const isCancelled = currentStatusCode === 4;

  if (isCancelled) {
    return (
      <div className="text-red-600 font-bold text-center mt-4">
        Pemesanan Dibatalkan.
      </div>
    );
  }

  return (
    <div className="timeline-container">
      {/* Garis progres yang dianimasikan */}
      <div 
        className="timeline-progress-line" 
        style={{ 
          width: `${relevantStatuses.length > 1 ? (currentIndex / (relevantStatuses.length - 1)) * 100 : 0}%` 
        }}
      ></div>

      {relevantStatuses.map((status, index) => (
        <div 
          key={status.code} 
          className={`timeline-step ${index <= currentIndex ? 'completed' : ''} ${index === currentIndex ? 'current' : ''}`}
        >
          <div className="timeline-node">
            {index < currentIndex && <span className="icon-check">&#10003;</span>} 
            {index === currentIndex && <span className="icon-current"></span>} {/* Bisa diganti dengan ikon lain */}
          </div>
          <div className="timeline-label">{status.label}</div>
        </div>
      ))}
    </div>
  );
}