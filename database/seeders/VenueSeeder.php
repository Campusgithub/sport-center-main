<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $venues = [
            [
                'name' => 'Lapangan Futsal',
                'type' => 'Futsal',
                'location' => 'Lantai 1',
                'price' => 200000,
                'image' => 'venues/futsal 1.jpeg', // harus sesuai dengan nama file di storage
                'facilities' => ['Toilet', 'Parkir', 'Ruang Ganti', 'Mushola']
            ],
            [
                'name' => 'Lapangan Basket',
                'type' => 'Basket',
                'location' => 'Indoor',
                'price' => 150000,
                'image' => 'venues/Basket 1.jpeg',
                'facilities' => ['Toilet', 'Parkir', 'Ruang Ganti']
            ],
            [
                'name' => 'Lapangan Badminton',
                'type' => 'Badminton',
                'location' => 'Lantai 2',
                'price' => 100000,
                'image' => 'venues/Bulutangkis.jpeg',
                'facilities' => ['Toilet', 'Parkir', 'Ruang Ganti', 'Mushola']
            ]
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}