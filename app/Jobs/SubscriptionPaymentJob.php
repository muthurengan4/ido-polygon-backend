<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

use Log, Auth, Setting, Exception;

use App\Helpers\Helper;

class SubscriptionPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try {

            $subscription_payment = $this->data['subscription_payment'];

            $user = User::where('id', $subscription_payment->user_id)->first();
            
            if (Setting::get('is_email_notification') == YES && $user) {
               
                $email_data['subject'] = Setting::get('site_name');

                $email_data['page'] = "emails.subscription.payment";

                $email_data['user'] = $user;

                $email_data['subscription_payment'] = $subscription_payment;

                $email_data['email'] = $user->email;

                $email_data['name'] = $user->name;

                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

            if (Setting::get('is_email_notification') == YES && Setting::get('admin_email_address')) {
               
                $email_data['subject'] = Setting::get('site_name');
                
                $email_data['page'] = "emails.subscription.payment";

                $email_data['user'] = $user;

                $email_data['subscription_payment'] = $subscription_payment;

                $email_data['email'] = $user->email;

                $email_data['name'] = $user->name;

                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

        } catch(Exception $e) {

            Log::info("Error ".print_r($e->getMessage(), true));

        }
    }
}
