<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'cashier', 'description' => 'Kasir'],
            ['name' => 'chef', 'description' => 'Koki'],
            ['name' => 'customer', 'description' => 'Pelanggan'],
        ];

        DB::table('roles')->insert($roles);
    }
}
