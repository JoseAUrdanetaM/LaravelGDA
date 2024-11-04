<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            [
                'dni' => '12345678',
                'id_reg' => 1,
                'id_com' => 1,
                'email' => 'customer1@example.com',
                'name' => 'John',
                'last_name' => 'Doe',
                'address' => '123 Main St',
                'date_reg' => now()
            ],
            [
                'dni' => '87654321',
                'id_reg' => 1,
                'id_com' => 2,
                'email' => 'customer2@example.com',
                'name' => 'Jane',
                'last_name' => 'Smith',
                'address' => null,
                'date_reg' => now()
            ],
            [
                'dni' => '12312312',
                'id_reg' => 2,
                'id_com' => 3,
                'email' => 'customer3@example.com',
                'name' => 'Mike',
                'last_name' => 'Johnson',
                'address' => '456 Another St',
                'date_reg' => now()
            ],
        ]);
    }
}
