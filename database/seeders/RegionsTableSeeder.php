<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('regions')->insert([
            ['id_reg' => 1, 'description' => 'Region 1', 'status' => 'A'],
            ['id_reg' => 2, 'description' => 'Region 2', 'status' => 'A'],
            ['id_reg' => 3, 'description' => 'Region 3', 'status' => 'I'],
        ]);
    }
}
