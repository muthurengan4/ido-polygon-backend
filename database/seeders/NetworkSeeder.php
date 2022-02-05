<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NetworkSeeder extends Seeder
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
                'key' => 'network_id',
                'value' => '97'
            ],
            [
                'key' => 'chain_id_hexacode',
                'value' => '0x38'
            ],
        ]);
    }
}
