<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Inertia\Inertia;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::all(); // ambil semua venue
        return Inertia::render('Venue', [
            'venues' => $venues
        ]);
    }

    public function show($id)
    {
        $venue = Venue::findOrFail($id);
        return Inertia::render('VenueDetail', [
            'venue' => $venue
        ]);
    }

    public function apiIndex()
    {
        return response()->json(\App\Models\Venue::all());
    }
}
