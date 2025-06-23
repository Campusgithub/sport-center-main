<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Lapangan Futsal',
                'image' => 'categories/futsal.jpg',
                'CompanyCode' => 'SPORT001',
                'Status' => 1,
                'isDeleted' => 0,
                'CreatedBy' => 'system',
                'CreatedDate' => now(),
            ],
            [
                'title' => 'Lapangan Basket',
                'image' => 'categories/basket.jpg',
                'CompanyCode' => 'SPORT001',
                'Status' => 1,
                'isDeleted' => 0,
                'CreatedBy' => 'system',
                'CreatedDate' => now(),
            ],
            [
                'title' => 'Lapangan Badminton',
                'image' => 'categories/badminton.jpg',
                'CompanyCode' => 'SPORT001',
                'Status' => 1,
                'isDeleted' => 0,
                'CreatedBy' => 'system',
                'CreatedDate' => now(),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 