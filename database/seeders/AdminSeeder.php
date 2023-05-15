<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();
        $admins = [
            [
                'name' => 'Super admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt(12345678),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        Admin::insert($admins);
    }
}
