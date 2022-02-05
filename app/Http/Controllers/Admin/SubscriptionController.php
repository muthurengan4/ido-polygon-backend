<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB, Hash, Setting, Auth, Validator, Exception, Enveditor;

use Carbon\Carbon;

use App\Helpers\Helper;

use App\Models\Subscription,App\Models\SubscriptionPayment, App\Models\User;

class SubscriptionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $paginate_count;

    public function __construct() {

        $this->middleware('auth:admin');
        
        $this->paginate_count = Setting::get('admin_take_count', 10);
    }

    /**
     * @method subscriptions_index()
     *
     * @uses To list out subscription details 
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function subscriptions_index(Request $request) {

        try {

            $base_query = Subscription::orderBy('created_at', 'desc');

            if($request->search_key) {

                $base_query = $base_query->where('subscriptions.title','LIKE','%'.$request->search_key.'%');
                      
            }

            $subscriptions = $base_query->paginate($this->paginate_count);
            
            return view('admin.subscriptions.index')
                        ->with('main_page','subscriptions-crud')
                        ->with('page','subscriptions')
                        ->with('sub_page' , 'subscriptions-view')
                        ->with('subscriptions' , $subscriptions);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());

        }
    }

    /**
     * @method subscriptions_create()
     *
     * @uses To create subscriptions details
     *
     * @created  Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function subscriptions_create() {

        $subscription = new Subscription;

        $subscription_plan_types = [PLAN_TYPE_MONTH,PLAN_TYPE_YEAR,PLAN_TYPE_DAY];

        return view('admin.subscriptions.create')
                    ->with('main_page','subscriptions-crud')
                    ->with('page' , 'subscriptions')
                    ->with('sub_page','subscriptions-create')
                    ->with('subscription', $subscription)
                    ->with('subscription_plan_types',$subscription_plan_types);           
    }

    /**
     * @method subscriptions_edit()
     *
     * @uses To display and update subscriptions details based on the subscription id
     *
     * @created Vithya R
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

            $subscription = Subscription::find($request->subscription_id);

            if(!$subscription) { 

                throw new Exception(tr('subscription_not_found'), 101);
            }

            $subscription_plan_types = [PLAN_TYPE_MONTH,PLAN_TYPE_YEAR,PLAN_TYPE_DAY];
           
            return view('admin.subscriptions.edit')
                    ->with('main_page','subscriptions-crud')
                    ->with('page' , 'subscriptions')
                    ->with('sub_page','subscriptions-view')
                    ->with('subscription' , $subscription)
                    ->with('subscription_plan_types',$subscription_plan_types); 
            
        } catch(Exception $e) {

            return redirect()->route('admin.subscriptions.index')->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method subscriptions_save()
     *
     * @uses To save the subscriptions details of new/existing subscription object based on details
     *
     * @created Vithya R
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
                'min_staking_balance' => 'required|numeric|min:1',
                'allowed_tokens' => 'required|numeric|min:1',
            ];

            Helper::custom_validator($request->all(),$rules);

            $subscription =  Subscription::find($request->subscription_id) ?? new Subscription;

            $subscription->title = $request->title;

            $subscription->description = $request->description ?: "";

            $subscription->min_staking_balance = $request->min_staking_balance;

            $subscription->allowed_tokens = $request->allowed_tokens;

            // Upload picture
            
            if($request->hasFile('picture')) {

                if($request->subscription_id) {

                    Helper::storage_delete_file($subscription->picture, COMMON_FILE_PATH); 
                    // Delete the old pic
                }

                $subscription->picture = Helper::storage_upload_file($request->file('picture'), COMMON_FILE_PATH);
            }

            if($subscription->save()) {

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
     * @created Vithya R 
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
      
            $subscription = Subscription::find($request->subscription_id);
            
            if(!$subscription) { 

                throw new Exception(tr('subscription_not_found'), 101);                
            }

            return view('admin.subscriptions.view')
                        ->with('main_page','subscriptions-crud')
                        ->with('page', 'subscriptions') 
                        ->with('sub_page','subscriptions-view') 
                        ->with('subscription' , $subscription);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method subscriptions_delete()
     *
     * @uses delete the subscription details based on subscription id
     *
     * @created Vithya R 
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

            $subscription = Subscription::find($request->subscription_id);
            
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
     * @created Vithya R
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

            $subscription = Subscription::find($request->subscription_id);

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
     * @method ()
     *
     * @uses Used to display all subscription available for all users
     *
     * @created Vithya R
     *
     * @updated
     *
     * @param object $request - subscripiton id
     *
     * @return redirect back page with status of the user verification
     */
    public function users_subscriptions_index(Request $request) {
        
        $user = User::where('id',$request->user_id)->first();

        $subscription_payments = SubscriptionPayment::where('user_id',$request->user_id)->paginate($this->paginate_count);
        
        $free_subscription = $subscription_payments->where('amount','=',0.00)->pluck('subscription_id') ?? [];

        $subscriptions = Subscription::where('status',APPROVED)
                            ->when($free_subscription, function ($q) use ($free_subscription) {
                                if($free_subscription->count() >= 1){
                                    return $q->whereNotIn('id', $free_subscription);
                                }
                            })->get();


        return view('admin.users.subscriptions.index')
                    ->with('main_page','users-crud')
                    ->with('page','users')
                    ->with('sub_page','users-view')
                    ->with('subscriptions',$subscriptions)
                    ->with('user', $user)
                    ->with('subscription_payments',$subscription_payments);
    }

    /**
     * @method users_subscription_payments_save()
     *
     * @uses Used to subscribe Particular subscription 
     *
     * @created Vithya R
     *
     * @updated
     *
     * @param object $request - subscripiton id
     *
     * @return redirect back page with status of the user verification
     */
    public function users_subscription_payments_save(Request $request) {
        
        try {
           
            DB::begintransaction();
            
            $rules = [
                'subscription_id' =>'required|exists:subscriptions,id',
                'user_id' => 'required|exists:users,id'
            ];

            Helper::custom_validator($request->all(),$rules);

            $subscription = Subscription::where('id',$request->subscription_id)->first();

            SubscriptionPayment::where('user_id', $request->user_id)->where('is_current_subscription', YES)->update(['is_current_subscription' => NO]);

            $previous_payment = SubscriptionPayment::where('user_id', $request->user_id)
                                            ->where('status', PAID)
                                            ->orderBy('created_at', 'desc')
                                            ->first();

            $subscription_payment = new SubscriptionPayment;

            $plan_type = $subscription->plan_type ?? PLAN_TYPE_MONTH; // For future purpose, dont remove

            $subscription_payment->expiry_date = date('Y-m-d H:i:s',strtotime("+{$subscription->plan} {$plan_type}"));


            if($previous_payment) {

                if (strtotime($previous_payment->expiry_date) >= strtotime(date('Y-m-d H:i:s'))) {
                    $subscription_payment->expiry_date = date('Y-m-d H:i:s', strtotime("+{$subscription->plan} {$plan_type}", strtotime($previous_payment->expiry_date)));
                }
            }

            $subscription_payment->subscription_id = $request->subscription_id;

            $subscription_payment->user_id = $request->user_id;

            $subscription_payment->payment_id = 'A-PAID-'.rand(1, 999999);

            $subscription_payment->status = PAID;

            $subscription_payment->amount = $subscription->amount ?? 0.00;

            $subscription_payment->payment_mode = COD;

            $subscription_payment->is_current_subscription = YES;

            $subscription_payment->plan = $subscription->plan;

            $subscription_payment->plan_type = $subscription->plan_type;

            $subscription_payment->cancel_reason = "";
            
            $subscription_payment->paid_date = date('Y-m-d H:i:s');

            if($subscription_payment->save()) {

                DB::commit();

                return redirect()->back()->with('flash_success',tr('subscription_payment_success'));
                
            }

            throw new Exception(tr('subscription_payment_failed'), 101);

        } catch(Exception $e){ 

            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());

        } 
    }

    /**
     * @method subscription_payments_delete()
     *
     * @uses Used to delete the payment record based on payment Id
     *
     * @created Vithya R
     *
     * @updated
     *
     * @param object $request - subsctiption payment Id
     *
     * @return 
     */
    public function users_subscription_payments_delete(Request $request) {

        try {

            DB::begintransaction();

            $subscripton_payment = SubscriptionPayment::find($request->subscription_payment_id);
            
            if(!$subscripton_payment) {

                throw new Exception(tr('subscription_payment_not_found'), 101);                
            }

            if($subscripton_payment->delete()) {

                DB::commit();

                return redirect()->route('admin.users.subscriptions_index',['user_id'=>$subscripton_payment->user_id])->with('flash_success',tr('subscription_payment_deleted_success'));  

            } 
            
            throw new Exception(tr('subscription_payment_failed_to_delete'));
            
        } catch(Exception $e){

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }     
    
    }


    /**
     * @method subscription_payments_index()
     *
     * @uses To create subscriptions details
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function subscription_payments_index(Request $request) {

        try {

            $base_query = SubscriptionPayment::orderBy('subscription_payments.id','desc');

            $title = tr('view_subscription_payments');

            if($request->today_revenue) {

                $base_query = $base_query->whereDate('subscription_payments.created_at',today());
            }

            if ($request->subscription_id) {

                $base_query = $base_query->where('subscription_payments.subscription_id', $request->subscription_id);

                $subscription = Subscription::find($request->subscription_id);

                $title = tr('view_subscription_payments').' - '. $subscription->title;
                
            }

            if ($request->payment_mode) {

                $base_query = $base_query->where('subscription_payments.payment_mode', $request->payment_mode);
                
            }

            if ($request->user_id) {

                $base_query = $base_query->where('subscription_payments.user_id', $request->user_id);

                $user = User::find($request->user_id);

                $title = tr('view_subscription_payments').' - '. $user->name;
                
            }
            
            if($request->search_key) {

                $search_key = $request->search_key;

                $base_query =  $base_query
                    ->orWhereHas('user', function($q) use ($search_key) {

                        return $q->where('users.name','LIKE','%'.$search_key.'%');

                    })->orWhereHas('subscription', function($q) use ($search_key) {

                        return $q->where('subscriptions.title','LIKE','%'.$search_key.'%');
                    })->orWhere('subscription_payments.payment_id','LIKE','%'.$search_key.'%');

            }

            $subscription_payments = $base_query->paginate($this->paginate_count);

            return view('admin.revenues.subscription_payments.index')
                        ->with('main_page','payments-crud')
                        ->with('page' , 'payments')
                        ->with('sub_page','subscription_payments')
                        ->with('title', $title)
                        ->with('subscription_payments', $subscription_payments);

        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
                 
    }

    /**
     * @method subscription_payments_view()
     *
     * @uses display the secified subscription details
     *
     * @created  Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function subscription_payments_view(Request $request) {

        try {

            $subscription_payment = SubscriptionPayment::where('subscription_payments.id', $request->subscription_payment_id)->first();

            if(!$subscription_payment) { 

                throw new Exception(tr('subscription_payment_not_found'), 101);                
            }

            return view('admin.revenues.subscription_payments.view')
                    ->with('main_page','payments-crud')
                    ->with('page' , 'payments')
                    ->with('sub_page','subscription-payments')
                    ->with('subscription_payment', $subscription_payment);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
                   
    }

    /**
     * @method subscriptions_bulk_action()
     * 
     * @uses To delete,approve,decline multiple users
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     *
     * @return success/failure message
     */
    public function subscriptions_bulk_action(Request $request) {

        try {

            $action_name = $request->action_name ;

            $subscription_ids = explode(',', $request->selected_subscriptions);

            if (!$subscription_ids && !$action_name) {

                throw new Exception(tr('subscription_action_is_empty'));

            }

            DB::beginTransaction();

            if($action_name == 'bulk_delete'){

                $subscription = Subscription::whereIn('id', $subscription_ids)->delete();

                if ($subscription) {

                    DB::commit();

                    return redirect()->back()->with('flash_success', tr('admin_subscriptions_delete_success'));

                }

                throw new Exception(tr('subscription_delete_failed'));

            } elseif($action_name == 'bulk_approve'){

                $subscription =  Subscription::whereIn('id', $subscription_ids)->update(['status' => APPROVED]);

                if ($subscription) {

                    DB::commit();

                    return back()->with('flash_success',tr('admin_subscriptions_approve_success'))->with('bulk_action','true');
                }

                throw new Exception(tr('subscriptions_approve_failed'));  

            } elseif($action_name == 'bulk_decline'){
                
                $subscription =  Subscription::whereIn('id', $subscription_ids)->update(['status' => DECLINED]);

                if ($subscription) {
                    
                    DB::commit();

                    return back()->with('flash_success',tr('admin_subscriptions_decline_success'))->with('bulk_action','true');
                }

                throw new Exception(tr('subscriptions_decline_failed')); 
            }

        } catch( Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error',$e->getMessage());
        }

    }



}
