<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategroiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cateogries = [
            ["name" => "Vegitable "],
            ["name" => "Fruit"]
        ];

        foreach($cateogries as $category){
            Category::firstOrCreate($category);
        }
    }
}
