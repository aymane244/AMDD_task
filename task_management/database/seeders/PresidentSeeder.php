<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PresidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userPresident = [
            [
                "first_name" => "Mohammed", 
                "last_name" => "Itti",
                "email" => "direction.amdd@gmail.com",
                "is_admin" => 1,
                "committee_id" => 1,
                "password" => Hash::make('Asso_Amdd23!!'),
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]
        ];
        DB::table('users')->insert($userPresident);
    }
}
