<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB, Hash, Setting, Validator, Exception, Enveditor, Log;

use App\Helpers\Helper;

use App\Models\User, App\Models\Subscription, App\Models\ProjectPayment, App\Models\Project;

use App\Repositories\PaymentRepository as PaymentRepo;

use App\Models\UserCard, App\Models\SubscriptionPayment;

class SubscriptionApiController extends Controller
{
    protected $loginUser;

    protected $skip, $take;

    public function __construct(Request $request) {

        Log::info(url()->current());

        Log::info("Request Data".print_r($request->all(), true));
        
        $this->loginUser = User::find($request->id);

        $this->skip = $request->skip ?: 0;

        $this->take = $request->take ?: (Setting::get('admin_take_count') ?: TAKE_COUNT);

        $this->timezone = $this->loginUser->timezone ?? "America/New_York";

    }

    /**
     * @method subscriptions_index()
     *
     * @uses To display all the subscription plans
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function subscriptions_index(Request $request) {

        try {

            $base_query = Subscription::where('subscriptions.status' , APPROVED);

            $subscriptions = $base_query->orderBy('created_at', 'asc')->get();

            return $this->sendResponse($message = '' , $code = '', $subscriptions);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /** 
     * @method subscriptions_payment_by_card()
     *
     * @uses pay for subscription using paypal
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param
     * 
     * @return JSON response
     *
     */

    public function subscriptions_payment_by_card(Request $request) {

        try {

            DB::beginTransaction();

            // Validation start

            $rules = [
                'subscription_id' => 'required|exists:subscriptions,id',
            ];

            $custom_errors = ['subscription_id' => api_error(129)];

            Helper::custom_validator($request->all(), $rules, $custom_errors);
            
            // Validation end

           // Check the subscription is available

            $subscription = Subscription::Approved()->firstWhere('id',  $request->subscription_id);

            if(!$subscription) {

                throw new Exception(api_error(129), 129);
                
            }

            $is_user_subscribed_free_plan = $this->loginUser->one_time_subscription ?? NO;

            if($subscription->amount <= 0 && $is_user_subscribed_free_plan) {

                throw new Exception(api_error(199), 199);
                
            }

            $request->request->add(['payment_mode' => CARD]);

            $total = $user_pay_amount = $subscription->amount ?? 0.00;

            if($user_pay_amount > 0) {

                $user_card = UserCard::where('user_id', $request->id)->firstWhere('is_default', YES);

                if(!$user_card) {

                    throw new Exception(api_error(120), 120); 

                }
                
                $request->request->add([
                    'total' => $total, 
                    'customer_id' => $user_card->customer_id,
                    'card_token' => $user_card->card_token,
                    'user_pay_amount' => $user_pay_amount,
                    'paid_amount' => $user_pay_amount,
                ]);


                $card_payment_response = PaymentRepo::subscriptions_payment_by_stripe($request, $subscription)->getData();
                
                if($card_payment_response->success == false) {

                    throw new Exception($card_payment_response->error, $card_payment_response->error_code);
                    
                }

                $card_payment_data = $card_payment_response->data;

                $request->request->add(['paid_amount' => $card_payment_data->paid_amount, 'payment_id' => $card_payment_data->payment_id, 'paid_status' => $card_payment_data->paid_status]);

            }

            $payment_response = PaymentRepo::subscriptions_payment_save($request, $subscription)->getData();

            if($payment_response->success) {
                
                DB::commit();

                $code = 118;

                return $this->sendResponse(api_success($code), $code, $payment_response->data);

            } else {

                throw new Exception($payment_response->error, $payment_response->error_code);
                
            }
        
        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }

    }

    /** 
     * @method subscriptions_payment_by_crypto()
     *
     * @uses pay for subscription using paypal
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param
     * 
     * @return JSON response
     *
     */

    public function subscriptions_payment_by_crypto(Request $request) {

        try {

            DB::beginTransaction();

            // Validation start

            $rules = [
                'subscription_id' => 'required|exists:subscriptions,id',
                'payment_id' => 'required'
            ];

            $custom_errors = ['subscription_id' => api_error(129)];

            Helper::custom_validator($request->all(), $rules, $custom_errors);
            
            // Validation end

           // Check the subscription is available

            $subscription = Subscription::Approved()->firstWhere('id',  $request->subscription_id);

            if(!$subscription) {

                throw new Exception(api_error(129), 129);
                
            }

            $is_user_subscribed_free_plan = $this->loginUser->one_time_subscription ?? NO;

            if($subscription->amount <= 0 && $is_user_subscribed_free_plan) {

                throw new Exception(api_error(199), 199);
                
            }

            $request->request->add(['payment_mode' => CRYPTO]);

            $total = $user_pay_amount = $subscription->amount ?? 0.00;

            $request->request->add(['paid_amount' => $total, 'payment_id' => $request->payment_id, 'paid_status' => UNPAID]);

            $payment_response = PaymentRepo::subscriptions_payment_save($request, $subscription)->getData();

            if($payment_response->success) {
                
                DB::commit();

                $code = 118;

                return $this->sendResponse(api_success($code), $code, $payment_response->data);

            } else {

                throw new Exception($payment_response->error, $payment_response->error_code);
                
            }
        
        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }

    }

    /**
     * @method subscriptions_history()
     *
     * @uses get the selected subscription details
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param integer $subscription_id
     *
     * @return JSON Response
     */
    public function subscriptions_history(Request $request) {

        try {

            $subscription_payments = SubscriptionPayment::BaseResponse()->where('user_id' , $request->id)->skip($this->skip)->take($this->take)->orderBy('subscription_payments.updated_at', 'desc')->get();
            
            foreach ($subscription_payments as $key => $value) {

                $value->plan_text = formatted_plan($value->plan ?? 0,$value->plan_type);

                $value->expiry_date_formatted = common_date($value->expiry_date, $this->timezone, 'M, d Y');
            
            }

            return $this->sendResponse($message = '' , $code = '', $subscription_payments);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method user_subscription_eligiable_check()
     *
     * @uses check the user eligiable for subscription creation
     *
     * @created Arun
     *
     * @updated 
     *
     * @param 
     * 
     * @return
     */
    public function user_subscription_eligiable_check(Request $request) {

        try {

            // Validation start

            $rules = [
                'project_unique_id' => 'required|exists:projects,unique_id',
                'staked_tokens' => 'required|numeric',
                'project_tokens_staked' => 'required|numeric',
                'busdx_tokens' => 'required|numeric',
            ];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);

            }

            $project = Project::firstWhere('unique_id', $request->project_unique_id);

            $current_date = strtotime(common_server_date(date("Y-m-d H:i:s"),"", "Y-m-d H:i:s"));

            $next_round_start_time = strtotime(date('Y-m-d H:i:s',strtotime('+'.$project->next_round_start_time.' minutes',strtotime($project->start_time))));

            // $next_round_start_time = strtotime(date('Y-m-d H:i:s',strtotime('+'.$project->next_round_start_time.' hour',strtotime($project->start_time))));

            $user_subscription = new \stdClass;

            $user_subscription->remaining_tokens = 0;

            if ($current_date < $next_round_start_time ) {
                
                $subscription = Subscription::whereRaw('CAST(min_staking_balance as DECIMAL(8,2)) <= ?', $request->staked_tokens)
                            ->orderByRaw('CAST(min_staking_balance AS DECIMAL)'. 'DESC')
                            ->first();

                if (!$subscription) {
                    
                    throw new Exception(api_error(182), 182);
                }

                $user_subscription->remaining_tokens = $subscription->allowed_tokens - $request->project_tokens_staked ?? 0;

                $user_subscription->subscription_round = 1;
            }
            else {

                if (Setting::get('min_stake_token') > $request->busdx_tokens) {
                    
                    throw new Exception(api_error(168), 168);
                }

                $user_subscription->subscription_round = 2;

            }

            return $this->sendResponse(api_success(119), 119, $data = $user_subscription);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method project_payment_save()
     *
     * @uses Save project payments
     *
     * @created Arun
     *
     * @updated 
     *
     * @param 
     * 
     * @return
     */
    public function project_payment_save(Request $request) {

        try {

            // Validation start

            $rules = [
                'project_unique_id' => 'required|exists:projects,unique_id',
                'staked_tokens' => 'required|numeric',
                'project_tokens_staked' => 'required|numeric',
                'project_tokens' => 'required|numeric',
                'busdx_tokens' => 'required|numeric',
            ];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);

            }

            $remaining_tokens = 0;

            $project = Project::firstWhere('unique_id', $request->project_unique_id);

            $current_date = strtotime(common_server_date(date("Y-m-d H:i:s"),"", "Y-m-d H:i:s"));

            $next_round_start_time = strtotime(date('Y-m-d H:i:s',strtotime('+'.$project->next_round_start_time.' minutes',strtotime($project->start_time))));

            if ($current_date < $next_round_start_time ) {

                $subscription = Subscription::whereRaw('CAST(min_staking_balance as DECIMAL(8,2)) <= ?', $request->staked_tokens)
                                ->orderByRaw('CAST(min_staking_balance AS DECIMAL)'. 'DESC')
                                ->first();

                if (!$subscription) {
                    
                    throw new Exception(api_error(182), 182);
                }

                $remaining_tokens = $subscription->allowed_tokens - $request->project_tokens_staked ?? 0;

            }
            else {

                if (Setting::get('min_stake_token') > $request->busdx_tokens) {
                    
                    throw new Exception(api_error(168), 168);
                }

            }

            $project_payment = ProjectPayment::where('user_id', $request->id)->where('project_id', $project->id)->first() ?? new ProjectPayment;

            if ($request->project_tokens > $remaining_tokens) {

                throw new Exception(api_error(183), 183);
            }

            $project_payment->used_tokens = $request->project_tokens + ($project_payment->used_tokens ?? 0);

            $project_payment->project_id = $project->id ?? 0;

            $project_payment->user_id = $user->id ?? 0;

            $project_payment->save();

            return $this->sendResponse(api_success(120), 120, $data = $project_payment);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }
}