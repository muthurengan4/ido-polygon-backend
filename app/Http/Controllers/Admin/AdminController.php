<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB, Hash, Setting, Auth, Validator, Exception, Enveditor;

use \App\Models\User, \App\Models\Project;

use \App\Models\SubscriptionPayment;

class AdminController extends Controller
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
     * @method main_dashboard()
     *
     * @uses Show the application dashboard.
     *
     * @created vithya
     *
     * @updated vithya
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function dashboard() {

        $data = new \stdClass;

        $data->total_users = User::count();

        $data->total_projects = Project::count();

        // $data->total_subscribers = SubscriptionPayment::count();

        // $data->total_revenue = SubscriptionPayment::where('status', PAID)->sum('subscription_payments.amount');

        $data->recent_users= User::Approved()->orderBy('id' , 'desc')->take(8)->get();

        $data->recent_projects= Project::Approved()->orderBy('id' , 'desc')->take(10)->get();

        $data->analytics = last_x_months_data(6);
       
        return view('admin.dashboard')
            ->with('page' , 'dashboard')
            ->with('data' ,$data);
    
    }
}
