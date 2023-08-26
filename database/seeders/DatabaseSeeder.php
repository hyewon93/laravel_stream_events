<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DonationSeeder::class);
        $this->call(FollowerSeeder::class);
        $this->call(MerchSaleSeeder::class);
        $this->call(SubscriberSeeder::class);
    }
}
