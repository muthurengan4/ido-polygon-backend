<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('settings')->insert([
            [
                'key' => 'network_token',
                'value' => 'Binance'
            ],
            [
                'key' => 'network_token_amount',
                'value' => '1'
            ],
            [
                'key' => 'lp_convertion_amount',
                'value' => '1000'
            ],
        ]);

    }
}
