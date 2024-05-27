<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use Database\Factories\LecturerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lecturer::factory('15')->create();
    }
}
