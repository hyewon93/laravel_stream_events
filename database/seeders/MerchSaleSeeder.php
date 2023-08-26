<?php

namespace Database\Seeders;

use App\Models\MerchSale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MerchSale::factory()->count(300)->create();
    }
}
