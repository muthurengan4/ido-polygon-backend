<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Helpers\Helper;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\Schema::hasTable('admins')) {

            $check_admin = \DB::table('admins')->where('email' , 'admin@polysparker.com')->count();

            if(!$check_admin) {

                \DB::table('admins')->insert([
                    [
                        'name' => 'Admin',
                        'unique_id' => 'admin-demo',
                        'email' => 'admin@launchpad.com',
                        'about' => 'About',
                        'password' => \Hash::make('123456'),
                        'picture' => env('APP_URL')."/placeholder.jpeg",
                        'status' => 1,
                        'timezone' => 'Asia/Kolkata',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                ]);

            }

            $check_test_admin = \DB::table('admins')->where('email' , 'test@polysparker.com')->count();

            if(!$check_test_admin) {

                \DB::table('admins')->insert([

                    [
                        'name' => 'Test',
                        'unique_id' => 'test-demo',
                        'email' => 'test@launchpad.com',
                        'password' => \Hash::make('123456'),
                        'about' => 'About',
                        'picture' => env('APP_URL')."/placeholder.jpeg",
                        'status' => 1,
                        'timezone' => 'Asia/Kolkata',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                ]);
            }
        
        }

        if(\Schema::hasTable('users')) {

            $check_user = \DB::table('users')->where('email' , 'user@polysparker.com')->count();

            if(!$check_user) {

                \DB::table('users')->insert([
                    [
                        'name' => 'User',
                        'first_name' => 'User',
                        'last_name' => 'Demo',
                        'unique_id' => 'user-demo',
                        'username' => 'user-demo',
                        'email' => 'user@polysparker.com',
                        'password' => \Hash::make('123456'),
                        'picture' => env('APP_URL')."/placeholder.jpeg",
                        'login_by' => 'manual',
                        'mobile' => '9836367763',
                        'device_type' => 'web',
                        'status' => USER_APPROVED,
                        'is_email_verified' => USER_EMAIL_VERIFIED,
                        'wallet_address' => rand(),
                        'token' => Helper::generate_token(),
                        'token_expiry' => Helper::generate_token_expiry(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                ]);

            }

            $check_test_user = \DB::table('users')->where('email' , 'test@polysparker.com')->count();

            if(!$check_test_user) {

                \DB::table('users')->insert([
                    [
                        'name' => 'Test',
                        'first_name' => 'User',
                        'last_name' => 'Demo',
                        'unique_id' => 'test-demo',
                        'username' => 'test-demo',
                        'email' => 'test@polysparker.com',
                        'password' => \Hash::make('123456'),
                        'picture' => env('APP_URL')."/placeholder.jpeg",
                        'login_by' => 'manual',
                        'mobile' => '9836367763',
                        'device_type' => 'web',
                        'status' => USER_APPROVED,
                        'is_email_verified' => USER_EMAIL_VERIFIED,
                        'wallet_address' => rand(),
                        'token' => Helper::generate_token(),
                        'token_expiry' => Helper::generate_token_expiry(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
                ]);
            }
        
        }
    }
}
