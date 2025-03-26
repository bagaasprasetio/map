<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'subs_start' => '2025-03-03',
                'subs_end' => '2025-04-17',
                'registered_by' => 2,
                'user_id' => 1
            ]
        ];

        foreach ($data as $key => $val){
            Subscription::create($val);
        }
    }
}
