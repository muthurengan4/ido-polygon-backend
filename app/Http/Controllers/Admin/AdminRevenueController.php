<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Helpers\Helper, App\Helpers\EnvEditorHelper;

use DB, Hash, Setting, Auth, Validator, Exception, Enveditor;

use App\Jobs\SendEmailJob;

use Carbon\Carbon;

use App\Repositories\PaymentRepository as PaymentRepo;

use App\Models\ProjectPayment,App\Models\SubscriptionPayment, App\Models\TokenPayment;

class AdminRevenueController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {

        $this->middleware('auth:admin');

        $this->skip = $request->skip ?: 0;
       
        $this->take = $request->take ?: (Setting::get('admin_take_count') ?: TAKE_COUNT);

    }

    /**
     * @method revenue_dashboard()
     *
     * @uses Show the revenue dashboard.
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function revenues_dashboard() {
        
        $data = new \stdClass;

        $data->subscription_payments = SubscriptionPayment::where('status',PAID)->sum('amount');

        $data->total_payments = $data->subscription_payments;

        $subscription_today_payments = SubscriptionPayment::where('status',PAID)->whereDate('created_at',today())->sum('amount');

        $data->today_payments = $subscription_today_payments;

        $data->analytics = revenue_graph(7);
        
        return view('admin.revenues.dashboard')
                    ->with('page' , 'revenue-dashboard')
                    ->with('data', $data);
    
    }


    /**
     * @method subscriptions_index()
     *
     * @uses To list out subscription details 
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function subscriptions_index() {
       
        $subscriptions = \App\Subscription::orderBy('updated_at','desc')->paginate(10);

        return view('admin.subscriptions.index')
                    ->with('page', 'subscriptions')
                    ->with('sub_page', 'subscriptions-view')
                    ->with('subscriptions', $subscriptions);
    }

    /**
     * @method subscriptions_create()
     *
     * @uses To create subscriptions details
     *
     * @created  Akshata
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function subscriptions_create() {

        $subscription = new \App\Subscription;

        $subscription_plan_types = [PLAN_TYPE_MONTH,PLAN_TYPE_YEAR,PLAN_TYPE_WEEK,PLAN_TYPE_DAY];

        return view('admin.subscriptions.create')
                    ->with('page' , 'subscriptions')
                    ->with('sub_page', 'subscriptions-create')
                    ->with('subscription', $subscription)
                    ->with('subscription_plan_types', $subscription_plan_types);           
    }

    /**
     * @method subscriptions_edit()
     *
     * @uses To display and update subscriptions details based on the instructor id
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param object $request - Subscription Id
     * 
     * @return redirect view page 
     *
     */
    public function subscriptions_edit(Request $request) {

        try {

            $subscription = \App\Subscription::find($request->subscription_id);

            if(!$subscription) { 

                throw new Exception(tr('subscrprion_not_found'), 101);
            }

            $subscription_plan_types = [PLAN_TYPE_MONTH,PLAN_TYPE_YEAR,PLAN_TYPE_WEEK,PLAN_TYPE_DAY];
           
            return view('admin.subscriptions.edit')
                    ->with('page', 'subscriptions')
                    ->with('sub_page', 'subscriptions-view')
                    ->with('subscription', $subscription)
                    ->with('subscription_plan_types', $subscription_plan_types); 
            
        } catch(Exception $e) {

            return redirect()->route('admin.subscriptions.index')->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method subscriptions_save()
     *
     * @uses To save the subscriptions details of new/existing subscription object based on details
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param object request - Subscrition Form Data
     *
     * @return success message
     *
     */
    public function subscriptions_save(Request $request) {

        try {

            DB::begintransaction();

            $rules = [
                'title'  => 'required|max:255',
                'description' => 'max:255',
                'amount' => 'required|numeric|min:0|max:10000000',
                'plan' => 'required',
                'plan_type' => 'required',
            
            ];

            Helper::custom_validator($request->all(),$rules);

            $subscription = $request->subscription_id ? \App\Subscription::find($request->subscription_id) : new \App\Subscription;

            if(!$subscription) {

                throw new Exception(tr('subscription_not_found'), 101);
            }

            $subscription->title = $request->title;

            $subscription->description = $request->description ?: "";

            $subscription->plan = $request->plan;

            $subscription->plan_type = $request->plan_type;

            $subscription->amount = $request->amount;
            
            $subscription->no_of_projects = $request->no_of_projects;

            $subscription->is_free = $request->is_free == YES ? YES :NO;
        
            $subscription->is_popular  = $request->is_popular == YES ? YES :NO;

            if( $subscription->save() ) {

                DB::commit();

                $message = $request->subscription_id ? tr('subscription_update_success')  : tr('subscription_create_success');

                return redirect()->route('admin.subscriptions.view', ['subscription_id' => $subscription->id])->with('flash_success', $message);
            } 

            throw new Exception(tr('subscription_saved_error') , 101);

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());
        } 

    }

    /**
     * @method subscriptions_view()
     *
     * @uses view the subscriptions details based on subscriptions id
     *
     * @created Akshata 
     *
     * @updated 
     *
     * @param object $request - Subscription Id
     * 
     * @return View page
     *
     */
    public function subscriptions_view(Request $request) {
       
        try {
      
            $subscription = \App\Subscription::find($request->subscription_id);
            
            if(!$subscription) { 

                throw new Exception(tr('subscription_not_found'), 101);                
            }

            return view('admin.subscriptions.view')
                        ->with('page', 'subscriptions') 
                        ->with('sub_page', 'subscriptions-view') 
                        ->with('subscription', $subscription);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method subscriptions_delete()
     *
     * @uses delete the subscription details based on subscription id
     *
     * @created Akshata 
     *
     * @updated  
     *
     * @param object $request - Subscription Id
     * 
     * @return response of success/failure details with view page
     *
     */
    public function subscriptions_delete(Request $request) {

        try {

            DB::begintransaction();

            $subscription = \App\Subscription::find($request->subscription_id);
            
            if(!$subscription) {

                throw new Exception(tr('subscription_not_found'), 101);                
            }

            if($subscription->delete()) {

                DB::commit();

                return redirect()->route('admin.subscriptions.index')->with('flash_success',tr('subscription_deleted_success'));   

            } 
            
            throw new Exception(tr('subscription_delete_failed'));
            
        } catch(Exception $e){

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }       
         
    }

    /**
     * @method subscriptions_status
     *
     * @uses To update subscription status as DECLINED/APPROVED based on subscriptions id
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param object $request - Subscription Id
     * 
     * @return response success/failure message
     *
     **/
    public function subscriptions_status(Request $request) {

        try {

            DB::beginTransaction();

            $subscription = \App\Subscription::find($request->subscription_id);

            if(!$subscription) {

                throw new Exception(tr('subscription_not_found'), 101);
                
            }

            $subscription->status = $subscription->status ? DECLINED : APPROVED ;

            if($subscription->save()) {

                DB::commit();

                $message = $subscription->status ? tr('subscription_approve_success') : tr('subscription_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }
            
            throw new Exception(tr('subscription_status_change_failed'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.subscriptions.index')->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method user_withdrawals
     *
     * @uses Display all stardom withdrawals
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param object $request - Subscription Id
     * 
     * @return response success/failure message
     *
     **/

    public function user_withdrawals(Request $request) {

        $base_query = \App\UserWithdrawal::orderBy('user_withdrawals.id', 'desc');

        if($request->search_key) {

            $search_key = $request->search_key;

            $base_query = $base_query->whereHas('user',function($query) use($search_key){

                return $query->where('users.name','LIKE','%'.$search_key.'%');

            })->orWhere('user_withdrawals.payment_id','LIKE','%'.$search_key.'%');
        }

        if($request->has('status')) {

            $base_query = $base_query->where('user_withdrawals.status',$request->status);
        }


        if($request->user_id) {

            $base_query = $base_query->where('user_withdrawals.user_id',$request->user_id);
        }


        $user = \App\Models\User::find($request->user_id)??'';

        $user_withdrawals = $base_query->paginate($this->take);
       
        return view('admin.user_withdrawals.index')
                ->with('page', 'content_creator-withdrawals')
                ->with('user', $user)
                ->with('user_withdrawals', $user_withdrawals);

    }


    /**
     * @method user_withdrawals_view
     *
     * @uses Display all stardom specified 
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param object $request - Subscription Id
     * 
     * @return response success/failure message
     *
     **/

    public function user_withdrawals_view(Request $request) {

          try {

            $user_withdrawal = \App\UserWithdrawal::where('id',$request->user_withdrawal_id)->first();

            if(!$user_withdrawal) { 

                throw new Exception(tr('user_withdrawal_not_found'), 101);                
            }  

            $billing_account = \App\UserBillingAccount::where('user_id', $user_withdrawal->user_id)->first();
       
            return view('admin.user_withdrawals.view')
                ->with('page', 'content_creator-withdrawals')
                ->with('user_withdrawal', $user_withdrawal)
                ->with('billing_account',$billing_account);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());

        }

    }

     /**
     * @method user_withdrawals_paynow()
     *
     * @uses 
     *
     * @created Akshata
     *
     * @updated
     *
     * @param Integer $request - stardom withdrawal id
     * 
     * @return view page
     *
     **/
    public function user_withdrawals_paynow(Request $request) {

        try {

            DB::begintransaction();

            $user_withdrawal = \App\UserWithdrawal::find($request->user_withdrawal_id);

            if(!$user_withdrawal) {

                throw new Exception(tr('user_withdrawal_not_found'),101);
                
            }

            $user_withdrawal->paid_amount = $user_withdrawal->requested_amount;

            $user_withdrawal->status = WITHDRAW_PAID;
            
            if($user_withdrawal->save()) {

                \App\Repositories\PaymentRepository::user_wallet_update_withdraw_paynow($user_withdrawal->requested_amount, $user_withdrawal->user_id);

                DB::commit();

                $email_data['subject'] = Setting::get('site_name');

                $email_data['page'] = "emails.users.withdrawals-approve";
    
                $email_data['data'] = $user_withdrawal->user;
    
                $email_data['email'] = $user_withdrawal->user->email ?? '';

                $email_data['message'] = tr('user_withdraw_paid_description');

                dispatch(new SendEmailJob($email_data));

                return redirect()->back()->with('flash_success',tr('payment_success'));
            }

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method user_withdrawals_reject()
     *
     * @uses 
     *
     * @created Akshata
     *
     * @updated
     *
     * @param Integer $request - stardom withdrawal id
     * 
     * @return view page
     *
     **/
    public function user_withdrawals_reject(Request $request) {

        try {

            DB::begintransaction();

            $user_withdrawal = \App\UserWithdrawal::find($request->user_withdrawal_id);

            if(!$user_withdrawal) {

                throw new Exception(tr('user_withdrawal_not_found'),101);
                
            }
            
            $user_withdrawal->status = WITHDRAW_DECLINED;
            
            if($user_withdrawal->save()) {

                DB::commit();

                PaymentRepo::user_wallet_update_withdraw_cancel($user_withdrawal->requested_amount, $user_withdrawal->user_id);

                $user_wallet_payment = \App\UserWalletPayment::where('id', $user_withdrawal->user_wallet_payment_id)->first();

                if($user_wallet_payment) {

                    $user_wallet_payment->status = USER_WALLET_PAYMENT_CANCELLED;

                    $user_wallet_payment->save();
                }

                
                $email_data['subject'] = Setting::get('site_name');

                $email_data['page'] = "emails.users.withdrawals-decline";
    
                $email_data['data'] = $user_withdrawal->user;
    
                $email_data['email'] = $user_withdrawal->user->email ?? '';

                $email_data['message'] = tr('user_withdraw_decline_description');

                dispatch(new SendEmailJob($email_data));

                return redirect()->back()->with('flash_success',tr('user_withdrawal_rejected'));
            }

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method user_wallets_index()
     *
     * @uses Display the lists of stardom users
     *
     * @created Akshata
     *
     * @updated
     *
     * @param -
     *
     * @return view page 
     */
    public function user_wallets_index(Request $request) {

        $base_query = \App\UserWallet::orderBy('created_at','DESC');

        if($request->search_key) {

            $search_key = $request->search_key;

            $base_query =  $base_query

                ->whereHas('user', function($q) use ($search_key) {

                    return $q->Where('users.name','LIKE','%'.$search_key.'%');

                })->orWhere('user_wallets.unique_id','LIKE','%'.$search_key.'%');
                        
        }

        if($request->user_id) {

            $base_query = $base_query->where('user_id',$request->user_id);
        }

        $user_wallets = $base_query->has('user')->where('total','>',0)->paginate(10);

        return view('admin.user_wallets.index')
                    ->with('page','user_wallets')
                    ->with('user_wallets' , $user_wallets);
    }

    /**
     * @method user_wallets_view()
     *
     * @uses display the transaction details of the perticulor stardom
     *
     * @created Akshata 
     *
     * @updated 
     *
     * @param object $request - stardom_wallet_id
     * 
     * @return View page
     *
     */
    public function user_wallets_view(Request $request) {
       
        try {
            
            $user_wallet = \App\UserWallet::where('user_id',$request->user_id)->first();
           
            if(!$user_wallet) { 

                $user_wallet = new \App\UserWallet;

                $user_wallet->user_id = $request->user_id;

                $user_wallet->save();

            }

            $user_wallet_payments = \App\UserWalletPayment::where('requested_amount','>',0)->where('user_id',$user_wallet->user_id)->orderBy('created_at','desc')->paginate(10);
                   
            return view('admin.user_wallets.view')
                        ->with('page', 'user_wallets') 
                        ->with('user_wallet', $user_wallet)
                        ->with('user_wallet_payments', $user_wallet_payments);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method token_payments_index()
     *
     * @uses Display the lists of subscriptions payments
     *
     * @created Bhawya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function token_payments_index(Request $request) {

        $base_query = TokenPayment::orderBy('created_at','desc');

        if($request->user_id) {

            $base_query = $base_query->where('user_id',$request->user_id);
        }

        if($request->search_key) {

            $search_key = $request->search_key;

            $base_query = $base_query
                ->whereHas('user',function($query) use($search_key){

                    return $query->where('users.name','LIKE','%'.$search_key.'%');
                                
                })->orWhere('token_payments.payment_id','LIKE','%'.$search_key.'%');
        }


        if($request->today_revenue){

            $base_query = $base_query->whereDate('token_payments.created_at', Carbon::today());

        }

        $token_payments = $base_query->paginate(10);
       
        return view('admin.revenues.token_payments.index')
                ->with('page','payments')
                ->with('sub_page','token-payments')
                ->with('token_payments',$token_payments);
    }


    /**
     * @method token_payments_view()
     *
     * @uses Display the subscription payment details for the users
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function token_payments_view(Request $request) {

        try {

            $token_payment = TokenPayment::where('id',$request->token_payment_id)->first();
           
            if(!$token_payment) {

                throw new Exception(tr('token_payment_not_found'), 1);
                
            }
           
            return view('admin.revenues.token_payments.view')
                    ->with('page','payments')
                    ->with('sub_page','token-payments')
                    ->with('token_payment',$token_payment);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    }


}
