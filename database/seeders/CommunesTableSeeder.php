<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('communes')->insert([
            ['id_com' => 1, 'id_reg' => 1, 'description' => 'Comuna A', 'status' => 'A'],
            ['id_com' => 2, 'id_reg' => 1, 'description' => 'Comuna B', 'status' => 'A'],
            ['id_com' => 3, 'id_reg' => 2, 'description' => 'Comuna C', 'status' => 'I'],
            ['id_com' => 4, 'id_reg' => 2, 'description' => 'Comuna D', 'status' => 'Trash'],
        ]);
    }
}
