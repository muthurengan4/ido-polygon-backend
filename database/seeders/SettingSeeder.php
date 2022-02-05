<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('settings')->delete();

        \DB::table('settings')->insert([
            [
                'key' => 'site_name',
                'value' => 'CGCOIN'
            ],
            [
                'key' => 'frontend_url',
                'value' => ''
            ],
            [
                'key' => 'backend_url',
                'value' => ''
            ],
            [
                'key' => 'tag_name',
                'value' => 'CGCOIN'
            ],
            [
                'key' => 'site_logo',
                'value' => env('APP_URL').'/logo.png'
            ],
            [
                'key' => 'site_icon',
                'value' => env('APP_URL').'/favicon.png'
            ],
            [
                'key' => 'version',
                'value' => 'v1.0.0'
            ],
            [
                'key' => 'default_lang',
                'value' => 'en'
            ],
            [
                'key' => 'currency',
                'value' => '$'
            ],
            [
                'key' => 'currency_code',
                'value' => 'USD'
            ],
            [
                'key' => 'tax_percentage',
                'value' => 10
            ],
            [
                'key' => 'admin_take_count',
                'value' => 12,
            ],
            [
                'key' => 'is_demo_control_enabled', // For demo purpose
                'value' => 0        
            ],
            [
                'key' => 'is_account_email_verification', // used to restrict the email verification process
                'value' => 0,
            ],
            [
                'key' => 'is_email_notification', // used restrict the send email 
                'value' => 1,
            ],
            [
                'key' => 'is_email_configured', // used check the email configuration 
                'value' => 1,
            ],
            [
                'key' => 'is_push_notification',
                'value' => 1,
            ],
            [
                'key' => 'MAILGUN_PUBLIC_KEY',
                'value' => ""
            ],
            [
                'key' => 'MAILGUN_PRIVATE_KEY',
                'value' => ""
            ],
            [
                'key' => 'stripe_publishable_key' ,
                'value' => "pk_test_uDYrTXzzAuGRwDYtu7dkhaF3",
            ],
            [
                'key' => 'stripe_secret_key' ,
                'value' => "sk_test_lRUbYflDyRP3L2UbnsehTUHW",
            ],
            [
                'key' => 'stripe_mode' ,
                'value' => "sandbox",
            ],          
            [
                'key' => 'token_expiry_hour',
                'value' => 1000000,
            ],
            [
                'key' => 'copyright_content',
                'value' => "Copyrights 2021. All rights reserved.",
            ],
            [
                'key' => 'contact_email',
                'value' => '',
            ],
            [
                'key' => 'contact_address',
                'value' => '',
            ],
            [
                'key' => 'contact_mobile',
                'value' => '',
            ],
            [
                'key' => 'gg_analytics',
                'value' => ""
            ],
            [
                'key' => 'head_scripts',
                'value' => ""
            ],
            [
                'key' => 'body_scripts',
                'value' => ""
            ],
            
            [
                'key' => "appstore_user",
                'value' => '',
            ],
            [
                'key' => "playstore_user",
                'value' => '',
            ],
            [
                'key' => 'facebook_link',
                'value' => '',
            ],
            [
                'key' => 'linkedin_link',
                'value' => '',
            ],
            [
                'key' => 'twitter_link',
                'value' => '',
            ],
            [
                'key' => 'pinterest_link',
                'value' => '',
            ],
            [
                'key' => 'instagram_link',
                'value' => '',
            ],
            [
                'key' => 'meta_title',
                'value' => '' // mins
            ],[
                'key' => 'meta_description',
                'value' => '' // mins
            ],
            [
                'key' => 'meta_author',
                'value' => '' // mins
            ],
            [
                'key' => 'meta_keywords',
                'value' => '' // mins
            ],
            [
                'key' => 'user_fcm_sender_id',
                'value' => '865212328189'
            ],
            [
                'key' => 'user_fcm_server_key',
                'value' => ''
            ],
            [
                'key' => 'admin_commission',
                'value' => 20
            ],
            [
                'key' => 'MAILGUN_PUBLIC_KEY',
                'value' => ""
            ],
            [
                'key' => 's3_bucket',
                'value' => NO
            ],
            [
                'key' => 'frontend_no_data_image',
                'value' => asset('images/no-data-found.svg')
            ],

            [
                'key' => 'is_mailgun_email_validate',
                'value' => NO
            ],
            [
                'key' => 'demo_admin_email',
                'value' => 'admin@launchpad.com'
            ],
            [
                'key' => 'demo_admin_password',
                'value' => '123456'
            ],
        
        ]);
    }
}
