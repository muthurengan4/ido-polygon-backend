<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            DemoDataSeeder::class,
            SettingSeeder::class,
            StaticPageSeeder::class,
            ConfigSeeder::class,
            CryptoUrlSeeder::class,
            EtherSeeder::class,
            ExchangeUrlSeeder::class,
            BinanceSeeder::class,
            NetworkSeeder::class,
            StakeLimitSeeder::class,
            ChainNetworkSeeder::class
        ]);
    }
}
