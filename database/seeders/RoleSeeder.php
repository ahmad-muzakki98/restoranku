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
            ['role_name' => 'admin', 'description' => 'Administrator'],
            ['role_name' => 'cashier', 'description' => 'Kasir'],
            ['role_name' => 'chef', 'description' => 'Koki'],
            ['role_name' => 'customer', 'description' => 'Pelanggan'],
        ];

        // DB::table('roles')->insert($roles);
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['role_name' => $role['role_name']], // cari berdasarkan role_name
                ['description' => $role['description']] // update deskripsi jika perlu
            );
        }
    }
}
