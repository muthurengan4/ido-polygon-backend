<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChainNetworkSeeder extends Seeder
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
                'key' => 'rpc_url',
                'value' => 'https://evmexplorer.testnet.velas.com/rpc'
            ],
            [
                'key' => 'chain_name',
                'value' => 'Velas - Testnet'
            ],
            [
                'key' => 'native_currency_name',
                'value' => 'Velas'
            ],
            [
                'key' => 'native_currency_symbol',
                'value' => 'VLX'
            ],
            [
                'key' => 'native_currency_decimals',
                'value' => 18
            ],
            [
                'key' => 'block_explorer_urls',
                'value' => 'https://evmexplorer.testnet.velas.com'
            ],
        ]);
    }
}
