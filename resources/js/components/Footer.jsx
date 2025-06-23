import React from 'react';

const Footer = () => {
    return (
        <footer className="bg-gray-100 py-10">
            <div className="text-center mb-6">
                <h2 className="text-2xl font-bold">Hubungi Kami</h2>
            </div>

            <div className="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center text-gray-700">
                {/* Email */}
                <div className="flex flex-col items-center">
                    <div className="text-3xl mb-2">📧</div>
                    <h4 className="font-semibold mb-1">Email</h4>
                    <p>sportcenterID@gmail.com</p>
                </div>

                {/* Telepon */}
                <div className="flex flex-col items-center">
                    <div className="text-3xl mb-2">📞</div>
                    <h4 className="font-semibold mb-1">Telepon</h4>
                    <p>+62 812-3456-7890</p>
                </div>

                {/* Alamat */}
                <div className="flex flex-col items-center">
                    <div className="text-3xl mb-2">📍</div>
                    <h4 className="font-semibold mb-1">Alamat</h4>
                    <p className="text-sm leading-tight">
                        Jl. Inspeksi Kalimalang, Tegal Danas,<br />
                        Cikarang Pusat, Bekasi, Jawa Barat
                    </p>
                </div>
            </div>

            <div className="text-center text-xs text-gray-500 mt-10">
                &copy; {new Date().getFullYear()} Sport Center. All rights reserved.
            </div>
        </footer>
    );
};

export default Footer;
