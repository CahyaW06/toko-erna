<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'admin',
                'password' => Hash::make('1qaz2wsx3edc'),
                'email' => 'admin@gmail.com'
            ]
        ];

        foreach ($data as $key => $value) {
            User::create($value);
        }


    }
}
