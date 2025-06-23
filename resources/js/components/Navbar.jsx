import React from 'react';
import { Link, usePage } from '@inertiajs/react';

const Navbar = () => {
    const { url } = usePage();

    const navLinkClass = (path) =>
        `transition-colors px-4 py-2 ${
            url === path
                ? 'text-red-600 font-extrabold border-b-2 border-red-600'
                : 'text-gray-700 hover:text-red-600'
        }`;

    return (
        <nav className="bg-white shadow-md sticky top-0 z-50">
            <div className="container mx-auto px-4">
                <div className="flex justify-between items-center h-16">
                    {/* Logo */}
                    <Link href="/" className="flex items-center space-x-1">
                        <span className="text-red-600 font-bold text-xl">SPORT</span>
                        <span className="font-bold text-xl">CENTER</span>
                    </Link>

                    {/* Navigation Links */}
                    <div className="hidden md:flex space-x-4">
                        <Link href="/" className={navLinkClass('/')}>Home</Link>
                        <Link href="/venue" className={navLinkClass('/venue')}>Venue</Link>
                        <Link href="/booking" className={navLinkClass('/booking')}>Booking</Link>
                        <Link href="/contact" className={navLinkClass('/contact')}>Contact</Link>
                    </div>

                    {/* Mobile Button - Opsional bisa ditambah dropdown menu */}
                    <div className="md:hidden">
                        <button className="text-gray-700 hover:text-red-600">
                            <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    );
};

export default Navbar;