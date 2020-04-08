<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        factory(Admin::class)->createMany([
            [
                "name" => "admin",
                "password" => Hash::make("admin12345"),
            ],
            [
                "name" => "david",
                "password" => Hash::make("david12345"),
            ],
            [
                "name" => "greg",
                "password" => Hash::make("greg12345"),
            ],
            [
                "name" => "andres",
                "password" => Hash::make("andres12345"),
            ],
        ]);
    }
}
