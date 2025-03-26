<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pangkalan;

class PangkalanSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'pangkalan_name' => 'CV Aryaduta',
                'pangkalan_address' => 'Ciomas',
                'transaction_quota' => 200,
                'user_id' => null
            ]
        ];

        foreach ($data as $key => $val){
            Pangkalan::create($val);
        }
    }
}
