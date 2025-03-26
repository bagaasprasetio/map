<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'user_name' => 'PT Mega Duta Pangkalan',
                'email' => 'megaduta_pangkalan@gmail.com',
                'password' => Hash::make('12345'),
                'role' => 'ap'
            ],
        ];

        foreach ($data as $key => $val){
            User::create($val);
        }
    }
}
