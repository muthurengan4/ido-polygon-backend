<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EtherSeeder extends Seeder
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
                'key' => 'ether_api_key',
                'value' => 'MA398CNCQN6P4SAXIDRKKSGDJQ7RUUHK7Y'
            ],
            [
                'key' => 'admin_wallet_address',
                'value' => ''
            ],
        ]);
    }
}
