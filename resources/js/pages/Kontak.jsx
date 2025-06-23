import React, { useState } from 'react';
{/* Footer bawah seperti sketch */}
<div className="mt-12 text-center text-gray-700">
  <h2 className="text-2xl font-bold mb-6">Hubungi Kami</h2>
  <div className="flex flex-col md:flex-row justify-center items-center gap-8">
    {/* Email */}
    <div className="flex flex-col items-center">
      <svg className="w-6 h-6 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 12H8m0 0H6m2 0h2m4 0h2m0 0h2m0 0v6m0-6v-2a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m10 0H6" />
      </svg>
      <p className="font-semibold">Email</p>
      <p>sportcenterID@gmail.com</p>
    </div>
    {/* Telepon */}
    <div className="flex flex-col items-center">
      <svg className="w-6 h-6 text-pink-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.684l1.5 4.5a1 1 0 01-.5 1.21l-2.25 1.13a11 11 0 005.5 5.5l1.13-2.25a1 1 0 011.21-.5l4.5 1.5a1 1 0 01.684.95V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
      </svg>
      <p className="font-semibold">Telepon</p>
      <p>+62 812-3456-7890</p>
    </div>
    {/* Alamat */}
    <div className="flex flex-col items-center">
      <svg className="w-6 h-6 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      <p className="font-semibold">Alamat</p>
      <p className="text-center">Jl. Inspeksi Kalimalang, Tegal Danas, Cikarang Pusat, Bekasi, Jawa Barat</p>
    </div>
  </div>
</div>
