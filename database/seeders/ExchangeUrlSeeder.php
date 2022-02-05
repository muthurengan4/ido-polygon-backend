<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExchangeUrlSeeder extends Seeder
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
                'key' => 'exchange_url',
                'value' => "http://exchange-token.codegama.info/"
            ]
        ]);
    }
}
