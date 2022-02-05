<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;

use DB, Hash, Setting, Validator, Exception, Enveditor;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

use App\Repositories\NotificationJobRepo as JobRepo;

use App\Models\Admin;

use App\Models\PasswordReset;

class AdminLoginController extends Controller
{   
    use AuthenticatesUsers;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    /**
     * Show the application’s login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {           
        return view('admin.auth.login');
    }

    protected function guard() {

        return Auth::guard('admin');

    }

    public function login(Request $request) {

        // Validate the form data
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:5'
        ]);

        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {

            if((Auth::guard('admin')->user()->is_sub_admin == YES) && (Auth::guard('admin')->user()->status) == DECLINED) {

                \Session::flash('flash_error', tr('sub_admin_account_decline_note'));
                
                Auth::guard('admin')->logout();

                return redirect()->route('admin.login')->with('flash_error', tr('username_password_not_match'));
            }

            $admin = Admin::find(\Auth::guard('admin')->user()->id);

            $admin->timezone = $request->has('timezone') ? $request->timezone : '';
            
            $admin->save();

            // if successful, then redirect to their intended location
            return redirect()->route('admin.dashboard')->with('flash_success',tr('login_success'));

        }
     
        // if unsuccessful, then redirect back to the login with the form data
     
        return redirect()->back()->with('flash_error', tr('username_password_not_match'));
    }

    public function logout() {
        
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->with('flash_success',tr('successfully_logout'));
    }

     public function showLinkRequestForm() {

        try {
            
            $is_email_configured = YES;

            if(!envfile('MAIL_USERNAME') || !envfile('MAIL_PASSWORD') || !envfile('MAIL_FROM_ADDRESS') || !envfile('MAIL_FROM_NAME')) {

                $is_email_configured = NO;

                throw new Exception(tr('email_not_configured'), 101);
                
            }
            
            return view('admin.auth.forgot')->with('is_email_configured', $is_email_configured);

        } catch(Exception $e){ 

            return redirect()->route('admin.login')->with('flash_error', $e->getMessage());

        } 
    }

     /**
     * @method forgot_password_update()
     *
     * @uses To update update the forgot password
     *
     * @created Jeevan
     *
     * @updated Jeevan
     *
     * @param object $request - Email id
     *
     * @return send mail to the admin
     */

    public function forgot_password_update(Request $request){

        try {
    
            DB::beginTransaction();
    
            // Check email configuration and email notification enabled by admin
    
            if(Setting::get('is_email_notification') != YES ) {
                
                throw new Exception(tr('email_not_configured'), 101);
                
            }
            
            $validator = Validator::make( $request->all(), [
                'email' => 'required|email|max:255|exists:admins',
            ]);
    
            if($validator->fails()) {
    
                $error = implode(',', $validator->messages()->all());
    
                throw new Exception($error, 101);
            }
    
            $admin = Admin::where('email' , $request->email)->first();
    
            if(!$admin) {
    
                throw new Exception(tr('invalid_user'), 101);
            }
    
            
            $token = app('auth.password.broker')->createToken($admin);
    
            PasswordReset::where('email', $admin->email)->delete();
    
            PasswordReset::insert([
                'email'=>$admin->email,
                'token'=>$token,
                'created_at'=>date('Y-m-d H:i:s')
            ]);

            JobRepo::admin_forgot_password_job($admin, $token);
            
            DB::commit();
    
            return redirect()->back()->with('flash_success',tr('mail_sent_successfully')); 
    
    
        } catch(Exception $e) {
    
            DB::rollback();
    
            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());
    
        }
    }
}     
