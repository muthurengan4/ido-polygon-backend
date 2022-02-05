<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB, Hash, Setting, Validator, Exception, Enveditor, Log;

use App\Helpers\Helper;

use App\Models\User, App\Models\UserCard;

use App\Repositories\NotificationJobRepo as JobRepo;

use Carbon\Carbon;

class UserAccountApiController extends Controller
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
     * @method register()
     *
     * @uses Registered user can register through manual or social login
     * 
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param Form data
     *
     * @return Json response with user details
     */
    public function register(Request $request) {
        try {

            DB::beginTransaction();

            $rules = [
                'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                'device_token' => '',
                'login_by' => 'required|in:manual,facebook,google,apple,linkedin,instagram',
            ];

            Helper::custom_validator($request->all(), $rules);

            $allowed_social_logins = ['facebook', 'google', 'apple', 'linkedin', 'instagram'];

            if(in_array($request->login_by, $allowed_social_logins)) {

                // validate social registration fields
                $rules = [
                    'social_unique_id' => 'required',
                    'first_name' => 'nullable|max:255|min:2',
                    'last_name' => 'nullable|max:255|min:1',
                    'email' => 'required|email|max:255',
                    'mobile' => 'nullable|digits_between:6,13',
                    'picture' => '',
                    'gender' => 'nullable|in:male,female,others',
                ];

                Helper::custom_validator($request->all(), $rules);

            } else {

                $rules = [

                        'name' => 'required|max:255|min:2',
                        'username' => 'required|max:255|min:1',
                        // 'first_name' => 'required|max:255|min:2',
                        // 'last_name' => 'required|max:255|min:1',
                        'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:255|min:2',
                        'password' => 'required|min:6',
                        'picture' => 'mimes:jpeg,jpg,bmp,png',
                    ];

                Helper::custom_validator($request->all(), $rules);
                // validate email existence

                $rules = ['email' => 'unique:users,email'];

                Helper::custom_validator($request->all(), $rules);

            }

            $user_details = User::firstWhere('username','=',$request->username);
           
            if($user_details) {

                throw new Exception(api_error(181), 181);

            }

            $user = User::firstWhere('email' , $request->email);

            $send_email = NO;

            // Creating the user

            if(!$user) {

                $user = new User;

                register_mobile($request->device_type);

                $send_email = YES;

                $user->registration_steps = 1;

            } else {

                if(in_array($user->status, [USER_PENDING , USER_DECLINED])) {

                    throw new Exception(api_error(1000), 1000);
                
                }

            }

            $user->name = $request->name ?? "";

            $user->first_name = $request->first_name ?? "";

            $user->last_name = $request->last_name ?? "";

            $user->email = $request->email ?? "";

            $user->mobile = $request->mobile ?? "";

            $user->username = $request->username ?? "";

            $user->timezone = $request->timezone ?? "";

            if($request->has('password')) {

                $user->password = Hash::make($request->password ?: "123456");

            }

            $user->gender = $request->gender ?? "male";

            $check_device_exist = User::firstWhere('device_token', $request->device_token);

            if($check_device_exist) {

                $check_device_exist->device_token = "";

                $check_device_exist->save();
            }

            $user->device_token = $request->device_token ?: "";

            $user->device_type = $request->device_type ?: DEVICE_WEB;

            $user->login_by = $request->login_by ?: 'manual';

            $user->social_unique_id = $request->social_unique_id ?: '';

            // Upload picture

            if($request->login_by == 'manual') {

                if($request->hasFile('picture')) {

                    $user->picture = Helper::storage_upload_file($request->file('picture') , PROFILE_PATH_USER);

                }

            } else {

                $user->picture = $request->picture ?: $user->picture;

            }   

            if($user->save()) {

                // Send welcome email to the new user:

                if($send_email) {

                    if($user->login_by == 'manual') {

                        JobRepo::user_register_job($user);

                    }

                }

                if(in_array($user->status , [USER_DECLINED , USER_PENDING])) {
                
                    $response = ['success' => false , 'error' => api_error(1000) , 'error_code' => 1000];

                    DB::commit();

                    return response()->json($response, 200);
               
                }

                if($user->is_email_verified == USER_EMAIL_VERIFIED) {

                    counter(); // For site analytics. Don't remove
                    
                    $data = User::find($user->id);

                    $response = ['success' => true, 'message' => api_success(101), 'data' => $data];

                } else {

                    $data = User::find($user->id);

                    $response = ['success' => true, 'message' => api_error(1001), 'code' => 1001, 'data' => $data];

                    DB::commit();

                    return response()->json($response, 200);

                }

            } else {

                throw new Exception(api_error(103), 103);

            }

            DB::commit();

            return response()->json($response, 200);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }
   
    }

    /**
     * @method login()
     *
     * @uses Registered user can login using their email & password
     * 
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - User Email & Password
     *
     * @return Json response with user details
     */
    public function login(Request $request) {

        try {
            
            DB::beginTransaction();

            $rules = [
                'wallet_address' => 'required',
            ];

            Helper::custom_validator($request->all(), $rules);

            $user = User::firstWhere('wallet_address', '=', $request->wallet_address);

            if ($user) {
                
                $user->timezone = $request->timezone ?? "";

                $user->save();
                
                DB::commit();
                
                counter(); // For site analytics. Don't remove

                return $this->sendResponse(api_success(101), 101, $user);

            }
            else{

                // Creating the user

                $user = new User;

                $user->name = "Unnamed";

                $user->wallet_address = $request->wallet_address ?? "";

                $user->email = $request->email ?? "";

                $user->username = $request->username ?? "";

                $user->password = $request->password ?? "";

                if($user->save()) {

                    DB::commit();

                    return $this->sendResponse(api_success(101), 101, $user);

                } else {

                    throw new Exception(api_error(103), 103);

                }

            }
            

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }
    
    }

    /**
     * @method forgot_password()
     *
     * @uses If the user forgot his/her password he can hange it over here
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - Email id
     *
     * @return send mail to the valid user
     */
    
    public function forgot_password(Request $request) {

        try {

            DB::beginTransaction();

            // Check email configuration and email notification enabled by admin

            if(Setting::get('is_email_notification') != YES ) {

                throw new Exception(api_error(106), 106);
                
            }
            
            $rules = ['email' => 'required|email|exists:users,email']; 

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            $user = User::firstWhere('email' , $request->email);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);
            }

            if($user->login_by != 'manual') {

                throw new Exception(api_error(118), 118);
                
            }

            // check email verification

            if($user->is_email_verified == USER_EMAIL_NOT_VERIFIED) {

                throw new Exception(api_error(1001), 1001);
            }

            // Check the user approve status

            if(in_array($user->status , [USER_DECLINED , USER_PENDING])) {
                throw new Exception(api_error(1000), 1000);
            }

            $token = app('auth.password.broker')->createToken($user);

            \App\Models\PasswordReset::where('email', $user->email)->delete();

            \App\Models\PasswordReset::insert([
                'email'=>$user->email,
                'token'=>$token,
                'created_at'=>Carbon::now()
            ]);

            JobRepo::user_forgot_password($user,$token);

            DB::commit();

            return $this->sendResponse(api_success(102), $success_code = 102, $data = []);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }
    
    }


    /**
     * @method reset_password()
     *
     * @uses To reset the password
     *
     * @created Ganesh
     *
     * @updated Ganesh
     *
     * @param object $request - Email id
     *
     * @return send mail to the valid user
     */
    
    public function reset_password(Request $request) {

        try {

            $rules = [
                'password' => 'required|confirmed|min:6',
                'reset_token' => 'required|string',
                'password_confirmation'=>'required'
            ]; 

            Helper::custom_validator($request->all(), $rules, $custom_errors =[]);

            DB::beginTransaction();

            $password_reset = \App\Models\PasswordReset::where('token', $request->reset_token)->first();

            if(!$password_reset){

                throw new Exception(api_error(163), 163);
            }
            
            $user = User::where('email', $password_reset->email)->first();

            $user->password = \Hash::make($request->password);

            $user->save();

            \App\Models\PasswordReset::where('email', $user->email) ->delete();

            DB::commit();

            $data = $user;

            return $this->sendResponse(api_success(153), $success_code = 153, $data);

        } catch(Exception $e) {

             DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }


   }

    /**
     * @method change_password()
     *
     * @uses To change the password of the user
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - Password & confirm Password
     *
     * @return json response of the user
     */
    public function change_password(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'password' => 'required|confirmed|min:6',
                'old_password' => 'required|min:6',
            ]; 

            Helper::custom_validator($request->all(), $rules, $custom_errors =[]);

            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);
            }

            if($user->login_by != "manual") {

                throw new Exception(api_error(118), 118);
                
            }

            if(Hash::check($request->old_password,$user->password)) {

                $user->password = Hash::make($request->password);
                
                if($user->save()) {

                    DB::commit();

                    JobRepo::user_change_password($user);

                    return $this->sendResponse(api_success(104), $success_code = 104, $data = []);
                
                } else {

                    throw new Exception(api_error(103), 103);   
                }

            } else {

                throw new Exception(api_error(108) , 108);
            }

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /** 
     * @method profile()
     *
     * @uses To display the user details based on user  id
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - User Id
     *
     * @return json response with user details
     */

    public function profile(Request $request) {

        try {

            $user = User::firstWhere('id' , $request->id);

            if(!$user) { 

                throw new Exception(api_error(1002) , 1002);
            }
            
            return $this->sendResponse($message = "", $success_code = "", $user);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }
    
    }
 
    /**
     * @method update_profile()
     *
     * @uses To update the user details
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param objecct $request : User details
     *
     * @return json response with user details
     */
    public function update_profile(Request $request) {

        try {

            DB::beginTransaction();

            // Validation start

            $rules = [
                    'name' => 'nullable|string|alpha_num|max:255',
                    'email' => 'email|unique:users,email,'.$request->id.'|regex:/(.+)@(.+)\.(.+)/i|max:255',
                    'mobile' => 'nullable|digits_between:6,13',
                    'picture' => 'nullable|mimes:jpeg,jpg,bmp,png',
            ];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            // Validation end
            
            $user = User::find($request->id);

            if(!$user) { 

                throw new Exception(api_error(1002) , 1002);
            }

            $user_details = User::where('id', '!=' , $request->id)
                ->firstWhere('username','=',$request->username);
           
            if($user_details) {

                throw new Exception(api_error(181), 181);

            }

            $user->name = $request->name ?: $user->name;

            $user->username = $request->username ?: $user->username;

            $user->first_name = $request->first_name ?: $user->first_name;

            $user->last_name = $request->last_name ?: $user->last_name;

            $user->unique_id = routefreestring(strtolower($request->name)).'-'.$user->id;
            
            if($request->has('email')) {

                $user->email = $request->email;
            }

            $user->mobile = $request->mobile ?: $user->mobile;

            $user->about = $request->filled('about') ? $request->about : "";

            $user->gender = $request->filled('gender') ? $request->gender : 'male';

            $user->address = $request->filled('address') ? $request->address : "";
            
            // Upload picture
            if($request->hasFile('picture') != "") {

                Helper::storage_delete_file($user->picture, PROFILE_PATH_USER); // Delete the old pic

                $user->picture = Helper::storage_upload_file($request->file('picture'), PROFILE_PATH_USER);
            
            }

            if($user->save()) {

                $data = User::find($user->id);

                DB::commit();

                return $this->sendResponse($message = api_success(111), $success_code = 111, $data);

            } else {    

                throw new Exception(api_error(103), 103);
            
            }

        } catch (Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }
   
    }

    /**
     * @method delete_account()
     * 
     * @uses Delete user account based on user id
     *
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param object $request - Password and user id
     *
     * @return json with boolean output
     */

    public function delete_account(Request $request) {

        try {

            DB::beginTransaction();

            $request->request->add([ 
                'login_by' => $this->loginUser ? $this->loginUser->login_by : "manual",
            ]);

            // Validation start

            $rules = ['password' => 'required_if:login_by,manual'];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            // Validation end

            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);
                
            }

            // The password is not required when the user is login from social. If manual means the password is required

            if($user->login_by == 'manual') {

                if(!Hash::check($request->password, $user->password)) {
         
                    throw new Exception(api_error(167), 167); 
                }
            
            }

            if($user->delete()) {

                DB::commit();

                return $this->sendResponse(api_success(103), $success_code = 103, $data = []);

            } else {

                throw new Exception(api_error(119), 119);
            }

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method user_project_eligiable_check()
     *
     * @uses check the user eligiable for project creation
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param 
     * 
     * @return
     */
    public function user_project_eligiable_check(Request $request) {

        try {

            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);

            }

            $user->is_project_create = $user->remaining_projects > 0 ? YES : NO;

            return $this->sendResponse($message = "", $code = 200, $data = $user);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }


    /**
     * @method username_validation()
     *
     * @uses
     * 
     * @created Bhawya N 
     *
     * @updated Bhawya N
     *
     * @param object $request - User Email & Password
     *
     * @return Json response with user details
     */
    public function username_validation(Request $request) {

        try {
            
            $rules = [
                // 'username' => 'required',
            ];

            Helper::custom_validator($request->all(), $rules);

            $user = User::firstWhere('username','=',$request->username);
           
            if($user) {

                throw new Exception(api_error(181), 181);

            }
            
            return $this->sendResponse($message = "", $code = "", []);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }
    
    }

    /**
     * @method logout()
     *
     * @uses Logout the user
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param 
     * 
     * @return
     */
    public function logout(Request $request) {

        return $this->sendResponse(api_success(106), 106);

    }

    /**
     * @method cards_list()
     *
     * @uses get the user payment mode and cards list
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param integer id
     * 
     * @return
     */

    public function cards_list(Request $request) {

        try {

            $user_cards = UserCard::where('user_id' , $request->id)->get();

            $card_payment_mode = $payment_modes = [];

            $card_payment_mode['name'] = "Card";

            $card_payment_mode['payment_mode'] = "card";

            $card_payment_mode['is_default'] = 1;

            array_push($payment_modes , $card_payment_mode);

            $data['payment_modes'] = $payment_modes;   

            $data['cards'] = $user_cards ? $user_cards : []; 

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }
    
    }
    
    /**
     * @method cards_add()
     *
     * @uses used to add card to the user
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param card_token
     * 
     * @return JSON Response
     */
    public function cards_add(Request $request) {

        try {

            if(Setting::get('stripe_secret_key')) {

                \Stripe\Stripe::setApiKey(Setting::get('stripe_secret_key'));

            } else {

                throw new Exception(api_error(121), 121);

            }

            // Validation start

            $rules = ['card_token' => 'required'];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            // Validation end
            
            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);
                
            }

            DB::beginTransaction();

            // Get the key from settings table

            $customer = \Stripe\Customer::create([
                    // "card" => $request->card_token,
                    // "card" => 'tok_visa',
                    "email" => $user->email,
                    "description" => "Customer for ".Setting::get('site_name'),
                    // 'payment_method' => $request->card_token,
                    // 'default_payment_method'
                    // 'source' => $request->card_token
                ]);

            $stripe = new \Stripe\StripeClient(Setting::get('stripe_secret_key'));

            $intent = \Stripe\SetupIntent::create([
              'customer' => $customer->id,
              'payment_method' => $request->card_token
            ]);

            $stripe->setupIntents->confirm($intent->id,['payment_method' => $request->card_token]);


            $retrieve = $stripe->paymentMethods->retrieve($request->card_token, []);
            
            $card_info_from_stripe = $retrieve->card ? $retrieve->card : [];

            // \Log::info("card_info_from_stripe".print_r($card_info_from_stripe, true));

            if($customer && $card_info_from_stripe) {

                $customer_id = $customer->id;

                $card = new UserCard;

                $card->user_id = $request->id;

                $card->customer_id = $customer_id;

                $card->card_token = $request->card_token ?? "NO-TOKEN";

                $card->card_type = $card_info_from_stripe->brand ?? "";

                $card->last_four = $card_info_from_stripe->last4 ?? '';

                $card->card_holder_name = $request->card_holder_name ?: $this->loginUser->name;

                // $cards->month = $card_details_from_stripe->exp_month ?? "01";

                // $cards->year = $card_details_from_stripe->exp_year ?? "01";

                // Check is any default is available

                $check_card = UserCard::where('user_id',$request->id)->count();

                $card->is_default = $check_card ? NO : YES;

                if($card->save()) {

                    if($user) {

                        // $user->user_card_id = $check_card ? $user->user_card_id : $card->id;

                        $user->save();
                    }

                    $data = UserCard::firstWhere('id' , $card->id);

                    DB::commit();

                    return $this->sendResponse(api_success(105), 105, $data);

                } else {

                    throw new Exception(api_error(114), 114);
                    
                }
           
            } else {

                throw new Exception(api_error(121) , 121);
                
            }

        } catch(Stripe_CardError | Stripe_InvalidRequestError | Stripe_AuthenticationError | Stripe_ApiConnectionError | Stripe_Error $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode() ?: 101);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode() ?: 101);
        }

    }

    /**
     * @method cards_delete()
     *
     * @uses delete the selected card
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param integer user_card_id
     * 
     * @return JSON Response
     */

    public function cards_delete(Request $request) {

        try {

            DB::beginTransaction();

            // validation start

            $rules = [
                'user_card_id' => 'required|integer|exists:user_cards,id,user_id,'.$request->id,
            ];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);
            
            // validation end

            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);
            }

            UserCard::where('id', $request->user_card_id)->delete();

            if($user->payment_mode = CARD) {

                // Check he added any other card

                if($check_card = UserCard::firstWhere('user_id' , $request->id)) {

                    $check_card->is_default =  DEFAULT_TRUE;

                    $user->user_card_id = $check_card->id;

                    $check_card->save();

                } else { 

                    $user->payment_mode = COD;

                    $user->user_card_id = DEFAULT_FALSE;
                
                }
           
            }

            // Check the deleting card and default card are same

            if($user->user_card_id == $request->user_card_id) {

                $user->user_card_id = DEFAULT_FALSE;

                $user->save();
            }
            
            $user->save();
                
            DB::commit();

            return $this->sendResponse(api_success(109), 109, $data = []);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * @method cards_default()
     *
     * @uses update the selected card as default
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param integer id
     * 
     * @return JSON Response
     */
    public function cards_default(Request $request) {

        try {

            DB::beginTransaction();

            // validation start

            $rules = [
                'user_card_id' => 'required|integer|exists:user_cards,id,user_id,'.$request->id,
            ];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);
            
            // validation end

            $user = User::find($request->id);

            if(!$user) {

                throw new Exception(api_error(1002), 1002);
            }
        
            $old_default_cards = UserCard::where('user_id' , $request->id)->where('is_default', YES)->update(['is_default' => NO]);

            $user_cards = UserCard::where('id' , $request->user_card_id)->update(['is_default' => YES]);

            $user->user_card_id = $request->user_card_id;

            $user->save();

            DB::commit();

            return $this->sendResponse(api_success(108), 108);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        }
    
    } 

    /**
     * @method payment_mode_default()
     *
     * @uses update the selected card as default
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param integer id
     * 
     * @return JSON Response
     */
    public function payment_mode_default(Request $request) {

        try {

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [

                'payment_mode' => 'required',

            ]);

            if($validator->fails()) {

                $error = implode(',',$validator->messages()->all());

                throw new Exception($error, 101);

            }

            $user = User::find($request->id);

            $user->payment_mode = $request->payment_mode ?: CARD;

            $user->save();           

            DB::commit();

            return $this->sendResponse($message = "Mode updated", $code = 200, $data = ['payment_mode' => $request->payment_mode]);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method regenerate_email_verification_code()
     *
     * @uses 
     *
     * @created vithya R
     *
     * @updated Vidhya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function regenerate_email_verification_code(Request $request) {

        try {

            DB::beginTransaction();

            $user = \App\Models\User::find($request->id);

            $user->verification_code = Helper::generate_email_code();

            // $user->verification_code_expiry = \Helper::generate_email_expiry();

            $user->save();

            JobRepo::user_register_job($user);

            DB::commit();

            return $this->sendResponse($message = api_success(308), $code = 308, $data = []);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method verify_email()
     *
     * @uses 
     *
     * @created vithya R
     *
     * @updated Vidhya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function verify_email(Request $request) {

        try {

            DB::beginTransaction();
            
            $rules = ['verification_code' => 'required|min:6|max:6'];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            $user = \App\Models\User::find($request->id);

            if($user->verification_code != $request->verification_code) {

                throw new Exception(api_error(309), 309);

            }

            $user->is_email_verified = USER_EMAIL_VERIFIED;

            $user->save();

            DB::commit();

            $data = User::find($user->id);

            return $this->sendResponse($message = api_success(117), $code = 117, $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }


}
