<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CryptoUrlSeeder extends Seeder
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
                'key' => 'crypto_url',
                'value' => 'https://api-testnet.bscscan.com'
            ],
            [
                'key' => 'contract_address',
                'value' => '0xCD78A3496EB4c63d083FE3b71e90aFf7E322bf8f'
            ]
        ]);
    }
}
