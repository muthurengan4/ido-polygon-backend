<?php

namespace App\Repositories;

use App\Helpers\Helper;

use Log, Validator, Setting, Exception, DB;

use App\Models\User, App\Models\SubscriptionPayment;

use App\Models\UserWallet,App\Models\UserWalletPayment;

use App\Models\MEventPayment;

class PaymentRepository {



     /**
     * @method user_wallets_payment_save()
     *
     * @uses used to save user wallet payment details
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $user_wallet_payment
     */

    public static function user_wallets_payment_save($request) {

        try {
            
            $user_wallet_payment = new UserWalletPayment;
            
            $user_wallet_payment->user_id = $request->id;

            $user_wallet_payment->to_user_id = $request->to_user_id ?? 0;

            $user_wallet_payment->received_from_user_id = $request->received_from_user_id ?? 0;

            $user_wallet_payment->user_billing_account_id = $request->user_billing_account_id ?: 0;
            
            $user_wallet_payment->payment_id = $request->payment_id ?:generate_payment_id();

            $user_wallet_payment->paid_amount = $user_wallet_payment->requested_amount = $request->paid_amount ?? 0.00;

            $user_wallet_payment->payment_type = $request->payment_type ?: WALLET_PAYMENT_TYPE_ADD;

            $user_wallet_payment->amount_type = $request->amount_type ?: WALLET_AMOUNT_TYPE_ADD;

            $user_wallet_payment->currency = Setting::get('currency') ?? "$";

            $user_wallet_payment->payment_mode = $request->payment_mode ?? CARD;

            $user_wallet_payment->paid_date = date('Y-m-d H:i:s');

            $user_wallet_payment->status = $request->paid_status ?: USER_WALLET_PAYMENT_PAID;

            $user_wallet_payment->m_event_id = $request->m_event_id ?? 0;


            if($request->file('bank_statement_picture')) {

                $user_wallet_payment->bank_statement_picture = Helper::storage_upload_file($request->file('bank_statement_picture'));

            }

            $user_wallet_payment->message = "";

            $user_wallet_payment->save();

            $user_wallet_payment->message = get_wallet_message($user_wallet_payment);

            $user_wallet_payment->save();

            if($user_wallet_payment->payment_type != WALLET_PAYMENT_TYPE_WITHDRAWAL && $user_wallet_payment->status == USER_WALLET_PAYMENT_PAID) {

                self::user_wallet_update($user_wallet_payment);
            }

            $response = ['success' => true, 'message' => 'paid', 'data' => $user_wallet_payment];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }
    
    }

    /**
     * @method user_wallets_payment_by_stripe()
     *
     * @uses pay for live videos using stripe
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $payment
     */

    public static function user_wallets_payment_by_stripe($request) {

        try {

            // Check stripe configuration
        
            $stripe_secret_key = Setting::get('stripe_secret_key');

            if(!$stripe_secret_key) {

                throw new Exception(api_error(107), 107);

            } 

            \Stripe\Stripe::setApiKey($stripe_secret_key);
           
            $currency_code = Setting::get('currency_code', 'USD') ?: 'USD';

            $total = intval(round($request->user_pay_amount * 100));

            $charge_array = [
                'amount' => $total,
                'currency' => $currency_code,
                'customer' => $request->customer_id,
                "payment_method" => $request->card_token,
                'off_session' => true,
                'confirm' => true,
            ];

            $stripe_payment_response = \Stripe\PaymentIntent::create($charge_array);

            $payment_data = [
                                'payment_id' => $stripe_payment_response->id ?? 'CARD-'.rand(),
                                'paid_amount' => $stripe_payment_response->amount/100 ?? $total,

                                'paid_status' => $stripe_payment_response->paid ?? true
                            ];

            $response = ['success' => true, 'message' => 'done', 'data' => $payment_data];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }



    /**
     * @method user_wallet_update()
     *
     * @uses pay for live videos using stripe
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $payment
     */

    public static function user_wallet_update($user_wallet_payment) {

        try {

            $user_wallet = UserWallet::where('user_id', $user_wallet_payment->user_id)->first() ?: new UserWallet;

            $user_wallet->user_id = $user_wallet_payment->user_id;

            if($user_wallet_payment->amount_type == WALLET_AMOUNT_TYPE_ADD) {

                $user_wallet->total += $user_wallet_payment->paid_amount;

                $user_wallet->remaining += $user_wallet_payment->paid_amount;

            } else {

                $user_wallet->used += $user_wallet_payment->paid_amount;

                $user_wallet->remaining -= $user_wallet_payment->paid_amount;
            }

            $user_wallet->save();

            $response = ['success' => true, 'message' => 'done', 'data' => $user_wallet];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }


   
    /**
     * @method subscriptions_payment_by_stripe()
     *
     * @uses Subscription payment - card
     *
     * @created Vithya R
     * 
     * @updated Vithya R
     *
     * @param object $subscription, object $request
     *
     * @return object $subscription
     */

    public static function subscriptions_payment_by_stripe($request, $subscription) {

        try {

            // Check stripe configuration

            $stripe_secret_key = Setting::get('stripe_secret_key');

            if(!$stripe_secret_key) {

                throw new Exception(api_error(107), 107);

            } 

            \Stripe\Stripe::setApiKey($stripe_secret_key);
           
            $currency_code = Setting::get('currency_code', 'USD') ?: "USD";

            $total = intval(round($request->user_pay_amount * 100));

            $charge_array = [
                'amount' => $total,
                'currency' => $currency_code,
                'customer' => $request->customer_id,
                "payment_method" => $request->card_token,
                'off_session' => true,
                'confirm' => true,
            ];

            $stripe_payment_response = \Stripe\PaymentIntent::create($charge_array);

            $payment_data = [
                'payment_id' => $stripe_payment_response->id ?? 'CARD-'.rand(),
                'paid_amount' => $stripe_payment_response->amount/100 ?? $total,
                'paid_status' => $stripe_payment_response->paid ?? true
            ];

            $response = ['success' => true, 'message' => 'done', 'data' => $payment_data];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method subscriptions_payment_save()
     *
     * @uses used to save user subscription payment details
     *
     * @created Vithya R
     * 
     * @updated Vithya R
     *
     * @param object $subscription, object $request
     *
     * @return object $subscription
     */

    public static function subscriptions_payment_save($request, $subscription) {

        try {

            $previous_payment = SubscriptionPayment::where('user_id' , $request->id)
                ->where('status', PAID)
                ->orderBy('created_at', 'desc')
                ->first();

            $user_subscription = new SubscriptionPayment;

            $user_subscription->expiry_date = date('Y-m-d H:i:s',strtotime("+{$subscription->plan} months"));

            if($previous_payment) {

                if (strtotime($previous_payment->expiry_date) >= strtotime(date('Y-m-d H:i:s'))) {
                    $user_subscription->expiry_date = date('Y-m-d H:i:s', strtotime("+{$subscription->plan} months", strtotime($previous_payment->expiry_date)));
                }
            }

            $user_subscription->subscription_id = $request->subscription_id;

            $user_subscription->user_id = $request->id;

            $user_subscription->payment_id = $request->payment_id ?: "NO-".rand();

            $user_subscription->status = $request->paid_status ?? PAID;

            $user_subscription->amount = $request->paid_amount ?? 0.00;

            $user_subscription->payment_mode = $request->payment_mode ?? CARD;

            $user_subscription->cancel_reason = $request->cancel_reason ?? '';
            
            $user_subscription->plan = $subscription->plan ?? $user_subscription->plan;

            $user_subscription->paid_date = date('Y-m-d H:i:s');

            $user_subscription->is_current_subscription = YES;

            $user_subscription->no_of_projects = $subscription->no_of_projects;

            $user_subscription->save();

            // update the earnings
            self::users_account_upgrade($request->id, $subscription->no_of_projects);

            $response = ['success' => true, 'message' => 'paid', 'data' => ['user_type' => YES, 'payment_id' => $request->payment_id]];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }
    
    }

    /**
     * @method users_account_upgrade()
     *
     * @uses add amount to user
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param integer $user_id, float $admin_amount, $user_amount
     *
     * @return - 
     */
    
    public static function users_account_upgrade($user_id, $no_of_projects) {

        if($user = User::find($user_id)) {

            $user->total_projects += $no_of_projects ?? 0;

            $user->remaining_projects += $no_of_projects ?? 0;

            $user->user_type = YES;

            $user->save();
        
        }
    
    }
    /**
     * @method user_wallet_update_withdraw_send()
     *
     * @uses 
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $payment
     */

    public static function user_wallet_update_withdraw_send($amount, $user_id) {
        
        try {

            $user_wallet = UserWallet::where('user_id', $user_id)->first() ?: new UserWallet;

            $user_wallet->user_id = $user_id;

            $user_wallet->remaining -= $amount;

            $user_wallet->onhold += $amount;

            $user_wallet->save();

            $response = ['success' => true, 'message' => 'done', 'data' => $user_wallet];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method user_wallet_update_withdraw_cancel()
     *
     * @uses pay for live videos using stripe
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $payment
     */

    public static function user_wallet_update_withdraw_cancel($amount, $user_id) {

        try {

            $user_wallet = UserWallet::where('user_id', $user_id)->first() ?: new UserWallet;

            $user_wallet->user_id = $user_id;

            $user_wallet->remaining += $amount;

            $user_wallet->onhold -= $amount;

            $user_wallet->save();

            $response = ['success' => true, 'message' => 'done', 'data' => $user_wallet];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method user_wallet_update_withdraw_paynow()
     *
     * @uses pay for live videos using stripe
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param object $request
     *
     * @return object $payment
     */

    public static function user_wallet_update_withdraw_paynow($amount, $user_id) {

        try {

            $user_wallet = UserWallet::where('user_id', $user_id)->first() ?: new UserWallet;

            $user_wallet->user_id = $user_id;

            $user_wallet->onhold -= $amount;

            $user_wallet->used += $amount;

            $user_wallet->save();

            $response = ['success' => true, 'message' => 'done', 'data' => $user_wallet];

            return response()->json($response, 200);

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }


}