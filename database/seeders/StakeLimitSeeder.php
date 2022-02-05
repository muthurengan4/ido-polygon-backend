<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StakeLimitSeeder extends Seeder
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
                'key' => 'min_stake_token',
                'value' => '100'
            ],
        ]);
    }
}
