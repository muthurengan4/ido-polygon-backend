<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;

use App\Helpers\Helper;

use DB, Hash, Setting, Auth, Validator, Exception, Enveditor;

use App\Repositories\NotificationJobRepo as JobRepo;

use App\Models\User, App\Models\InvestedProject;

use App\Models\Project;

use Excel;

use App\Exports\UsersExport;

class AdminUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {

        $this->middleware('auth:admin');
       
        $this->paginate_count = 10;
        
    }

    /**
     * @method users_index()
     *
     * @uses To list out users details 
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function users_index(Request $request) {

        $base_query = User::orderBy('created_at','desc');

        $page = 'users'; $sub_page = 'users-view';

        $title = tr('view_users');

        if($request->search_key) {

            $search_key = $request->search_key;

            $search_user_ids = User::where('users.email', 'LIKE','%'.$search_key.'%')
                            ->orWhere('users.name', 'LIKE','%'.$search_key.'%')
                            ->orWhere('users.mobile', 'LIKE','%'.$search_key.'%')
                            ->pluck('id');

            $base_query = $base_query->whereIn('users.id',$search_user_ids);

        }

        if($request->status != '') {

            $base_query = $base_query->where('users.status', $request->status);
        }

        if($request->document_status != '') {

            $base_query = $base_query->where('users.is_document_verified', $request->document_status);
        }

        $users = $base_query->paginate($this->paginate_count);

        return view('admin.users.index')
                    ->with('page', $page)
                    ->with('sub_page', $sub_page)
                    ->with('title', $title)
                    ->with('users', $users);
    
    }

    /**
     * @method users_create()
     *
     * @uses To create user details
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function users_create() {

        $user = new User;


        return view('admin.users.create')
                    ->with('page', 'users')
                    ->with('sub_page','users-create')
                    ->with('user', $user);           
   
    }


    /**
     * @method users_edit()
     *
     * @uses To display and update user details based on the user id
     *
     * @created vidhya
     *
     * @updated 
     *
     * @param object $request - User Id
     * 
     * @return redirect view page 
     *
     */
    public function users_edit(Request $request) {

        try {

            $user = User::find($request->user_id);

            if(!$user) { 

                throw new Exception(tr('user_not_found'), 101);
            }

            return view('admin.users.edit')
                    ->with('page', 'users')
                    ->with('sub_page', 'users-view')
                    ->with('user', $user); 
            
        } catch(Exception $e) {

            return redirect()->route('admin.users.index')->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method users_save()
     *
     * @uses To save the users details of new/existing user object based on details
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param object request - User Form Data
     *
     * @return success message
     *
     */
    public function users_save(Request $request) {

        try {

            DB::begintransaction();

            $rules = [                
                'wallet_address' => $request->user_id ? 'required|unique:users,wallet_address,'.$request->user_id.',id' : 'required|unique:users,wallet_address,NULL,id',
                'name' => 'required|max:191',
                // 'username' => 'nullable|unique:users,username,'.$request->user_id.'|max:255',
                // 'email' => $request->user_id ? 'required|email|max:191|unique:users,email,'.$request->user_id.',id' : 'required|email|max:191|unique:users,email,NULL,id',
                'password' => $request->user_id ? "" : 'required|min:6|confirmed',
                // 'mobile' => $request->mobile ? 'digits_between:6,13' : '',
                'picture' => 'mimes:jpg,png,jpeg',
                'user_id' => 'exists:users,id|nullable',
                'cover' => 'nullable|mimes:jpeg,bmp,png,jpg',
                'gender' => 'nullable|in:male,female,others',
            ];

            Helper::custom_validator($request->all(),$rules);

            $user = $request->user_id ? User::find($request->user_id) : new User;

            $is_new_user = NO;

            if($user->id) {

                $message = tr('user_updated_success'); 

            } else {

                $is_new_user = YES;

                $user->password = ($request->password) ? \Hash::make($request->password) : null;

                $message = tr('user_created_success');

                $user->email_verified_at = date('Y-m-d H:i:s');

                $user->is_email_verified = USER_EMAIL_VERIFIED;

                $user->token = Helper::generate_token();

                $user->token_expiry = Helper::generate_token_expiry();
                
                $user->login_by = $request->login_by ?: 'manual';

            }

            $user->name = $request->name;

            $user->wallet_address = $request->wallet_address ?? $user->wallet_address;

            $user->email = $request->email ?? "";

            $user->mobile = $request->mobile ?: "";

            $user->gender = $request->gender ?: "male";

            $username = $request->username ?: $user->username;

            $user->unique_id = $user->username = routefreestring(strtolower($request->name)).'-'.$request->name;
            
            // Upload picture
            
            if($request->hasFile('picture')) {

                if($request->user_id) {

                    Helper::storage_delete_file($user->picture, COMMON_FILE_PATH); 
                    // Delete the old pic
                }

                $user->picture = Helper::storage_upload_file($request->file('picture'), COMMON_FILE_PATH);
            }

            if($user->save()) {

                if($is_new_user == YES) {

                    $user->is_verified = USER_EMAIL_VERIFIED;

                    $user->save();

                }


                DB::commit(); 

                return redirect(route('admin.users.view', ['user_id' => $user->id]))->with('flash_success', $message);

            } 

            throw new Exception(tr('user_save_failed'));
            
        } catch(Exception $e){ 

            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());

        } 

    }


    /**
     * @method users_view()
     *
     * @uses Display the specified user details based on user_id
     *
     * @created Vidhya 
     *
     * @updated 
     *
     * @param object $request - User Id
     * 
     * @return View page
     *
     */
    public function users_view(Request $request) {
       
        try {
      
            $user = User::find($request->user_id);

            if(!$user) { 

                throw new Exception(tr('user_not_found'), 101);                
            }

            return view('admin.users.view')
                        ->with('page', 'users') 
                        ->with('sub_page','users-view') 
                        ->with('user' , $user);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method users_delete()
     *
     * @uses delete the user details based on user id
     *
     * @created Vidhya 
     *
     * @updated  
     *
     * @param object $request - User Id
     * 
     * @return response of success/failure details with view page
     *
     */
    public function users_delete(Request $request) {

        try {

            DB::begintransaction();

            $user = User::find($request->user_id);
            
            if(!$user) {

                throw new Exception(tr('user_not_found'), 101);                
            }

            if($user->delete()) {

                DB::commit();


                return redirect()->route('admin.users.index',['page'=>$request->page])->with('flash_success',tr('user_deleted_success'));   

            } 
            
            throw new Exception(tr('user_delete_failed'));
            
        } catch(Exception $e){

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }       
         
    }

    /**
     * @method users_status
     *
     * @uses To update user status as DECLINED/APPROVED based on users id
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param object $request - User Id
     * 
     * @return response success/failure message
     *
     **/
    public function users_status(Request $request) {

        try {

            DB::beginTransaction();

            $user = User::find($request->user_id);

            if(!$user) {

                throw new Exception(tr('user_not_found'), 101);
                
            }

            $user->status = $user->status ? DECLINED : APPROVED ;

            if($user->save()) {

                DB::commit();

                $message = $user->status ? tr('user_approve_success') : tr('user_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }
            
            throw new Exception(tr('user_status_change_failed'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.users.index')->with('flash_error', $e->getMessage());

        }

    }

    public function users_excel(Request $request) {

        try{
            $file_format = '.xlsx';

            $filename = routefreestring(Setting::get('site_name'))."-".date('Y-m-d-h-i-s')."-".uniqid().$file_format;

            return Excel::download(new UsersExport($request), $filename);

        } catch(\Exception $e) {

            return redirect()->route('admin.users.index')->with('flash_error' , $e->getMessage());

        }

    }

     /**
     * @method users_bulk_action()
     * 
     * @uses To delete,approve,decline multiple users
     *
     * @created Ganesh
     *
     * @updated 
     *
     * @param 
     *
     * @return success/failure message
     */
    public function users_bulk_action(Request $request) {

        try {
            
            $action_name = $request->action_name ;

            $user_ids = explode(',', $request->selected_users);

            if (!$user_ids && !$action_name) {

                throw new Exception(tr('user_action_is_empty'));

            }

            DB::beginTransaction();

            if($action_name == 'bulk_delete'){

                $user = User::whereIn('id', $user_ids)->delete();

                if ($user) {

                    DB::commit();

                    return redirect()->back()->with('flash_success',tr('admin_users_delete_success'));

                }

                throw new Exception(tr('user_delete_failed'));

            }elseif($action_name == 'bulk_approve'){

                $user = User::whereIn('id', $user_ids)->update(['status' => USER_APPROVED]);

                if ($user) {

                    DB::commit();

                    return back()->with('flash_success',tr('admin_users_approve_success'))->with('bulk_action','true');
                }

                throw new Exception(tr('users_approve_failed'));  

            }elseif($action_name == 'bulk_decline'){
                
                $user = User::whereIn('id', $user_ids)->update(['status' => USER_DECLINED]);

                if ($user) {
                    
                    DB::commit();

                    return back()->with('flash_success',tr('admin_users_decline_success'))->with('bulk_action','true');
                }

                throw new Exception(tr('users_decline_failed')); 
            }

        } catch( Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error',$e->getMessage());
        }

    }

    /**
     * @method users_verify_status()
     *
     * @uses verify for the user
     *
     * @created Vithya R
     *
     * @updated
     *
     * @param integer $request id
     *
     * @return redirect back page with status of the user verification
     */
    public function users_verify_status(Request $request) {

        try {

            DB::beginTransaction();

            $user = User::find($request->user_id);

            if(!$user) {

                throw new Exception(tr('user_not_found'), 101);
                
            }

            $user->is_verified = $user->is_verified ? USER_EMAIL_NOT_VERIFIED : USER_EMAIL_VERIFIED;
            
            $user->is_email_verified = $user->is_email_verified ? USER_EMAIL_NOT_VERIFIED : USER_EMAIL_VERIFIED;

            if($user->save()) {

                DB::commit();

                $message = $user->is_email_verified ? tr('user_verify_success') : tr('user_unverify_success');

                return redirect()->back()->with('flash_success', $message);
            }
            
            throw new Exception(tr('user_verify_change_failed'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method users_invested_projects()
     *
     * @uses To list out users details 
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function invested_projects(Request $request) {

        $base_query = \App\Models\ProjectStack::orderBy('created_at','desc');

        $title = tr('invested_projects');

        if($request->user_id) {

            $user = User::find($request->user_id);

            $base_query = $base_query->where('user_id',$request->user_id);

            $title = tr('invested_projects').' - '. $user->name;
        }

        if($request->project_id) {

            $project = Project::find($request->project_id);

            $base_query = $base_query->where('project_id',$request->project_id);

            $title = tr('invested_users').' - '. $project->name;
        }

        if($request->search_key) {

            $base_query = $base_query
                ->whereHas('project', function($q) use ($request) {

                    return $q->Where('projects.name','LIKE','%'.$request->search_key.'%');

                })->orWhereHas('user', function($q) use ($request) {

                    return $q->Where('users.name','LIKE','%'.$request->search_key.'%');

                })->orWhere('project_stacks.wallet_address','LIKE','%'.$request->search_key.'%');
        }

        $invested_projects = $base_query->paginate($this->paginate_count);

        return view('admin.invested_projects.index')
                    ->with('page', 'invested-projects')
                    ->with('sub_page', 'invested-projects')
                    ->with('title', $title)
                    ->with('invested_projects', $invested_projects);
    
    }

    /**
     * @method invested_projects()
     *
     * @uses To list out users details 
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function invested_projects_view(Request $request) {


        try {
      
            $invested_project = InvestedProject::find($request->invested_project_id);

            if(!$invested_project) { 

                throw new Exception(tr('user_not_found'), 101);                
            }

            return view('admin.invested_projects.view')
                    ->with('page', 'invested-projects')
                    ->with('sub_page', 'invested-projects') 
                    ->with('invested_project', $invested_project);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method invested_projects()
     *
     * @uses To list out users details 
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function invested_projects_claim(Request $request) {


        try {
        
            DB::beginTransaction();

            $invested_project = InvestedProject::find($request->invested_project_id);

            if(!$invested_project) { 

                throw new Exception(tr('user_not_found'), 101);                
            }

            $invested_project->claim_token = sha1(uniqid(time(), true));
            
            $invested_project->save();

            DB::commit();

            return Redirect::away(Setting::get('frontend_url').'send-token-user/'.$invested_project->claim_token);
            
        } catch (Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

}
