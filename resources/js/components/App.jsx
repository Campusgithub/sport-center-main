import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Navbar from './Navbar';
import Home from '../pages/Home';
import Venue from '../pages/Venue';
import VenueDetail from '../pages/VenueDetail';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';
import Booking from '../pages/Booking';
import Kontak from '../pages/Kontak';

function App() {
    return (
        <div className="min-h-screen bg-gray-50">
            <Routes>
                <Route path="/login" element={<Login />} />
                <Route path="/admin" element={<Dashboard />} />
                <Route path="/" element={
                    <>
                        <Navbar />
                        <Home />
                    </>
                } />
                <Route path="/venue" element={
                    <>
                        <Navbar />
                        <Venue />
                    </>
                } />
                <Route path="/venue/:id" element={
                    <>
                        <Navbar />
                        <VenueDetail />
                    </>
                } />
                <Route path="/booking" element={
                    <>
                        <Navbar />
                        <Booking />
                    </>
                } />
                <Route path="/kontak" element={
                    <>
                        <Navbar />
                        <Kontak />
                    </>
                } />
            </Routes>
        </div>
    );
}

export default App; 