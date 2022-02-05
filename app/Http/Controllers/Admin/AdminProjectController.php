<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB, Hash, Setting, Auth, Validator, Exception, Enveditor;

use \App\Helpers\Helper;

use Carbon\Carbon;

use \App\Models\Project, \App\Models\User, \App\Models\ProjectPayment, \App\Models\ProjectOwnerTransaction;

use App\Repositories\NotificationJobRepo as JobRepo;

class AdminProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {

        $this->middleware('auth:admin');
       
        $this->paginate_count = 10;

        $this->timezone = Auth::guard('admin')->user()->timezone ?? 'Asia/Kolkata';
        
    }

    /**
     * @method projects_index()
     *
     * @uses Used to list the projects
     *
     * @created vithya
     *
     * @updated vithya  
     *
     * @param -
     *
     * @return List of pages   
     */

    public function projects_index(Request $request) {

         $base_query = Project::orderBy('created_at','desc');

          $title = tr('list_projects');

        if($request->search_key) {

            $search_key = $request->search_key;

            $search_project_ids =  $search_project_ids = Project::whereHas('user', function($q) use ($search_key) {

                                                    return $q->Where('users.name','LIKE','%'.$search_key.'%')->orWhere('users.username','LIKE','%'.$search_key.'%');

                                                })->orWhere('projects.name', 'LIKE','%'.$search_key.'%')
                                                ->pluck('id');
            $base_query = $base_query->whereIn('projects.id',$search_project_ids);

        }

        if($request->status) {

            switch ($request->status) {

                case SORT_BY_APPROVED:
                    $base_query = $base_query->where('projects.status', APPROVED);
                    break;

                case SORT_BY_DECLINED:
                    $base_query = $base_query->where('projects.status', DECLINED);
                    break;               

                default:
                    $base_query = $base_query;
                    break;
            }
        }

        if($request->publish_status != '') {

            $base_query = $base_query->where('projects.publish_status', $request->publish_status);
        }

        if($request->user_id != '') {

            $base_query = $base_query->where('user_id',$request->user_id);

            $user = User::find($request->user_id);

            if(!$user) { 

                throw new Exception(tr('user_not_found'), 101);
            }

            $title = tr('list_projects')." - ".$user->name;
        }

        $projects = $base_query->paginate($this->paginate_count);

        return view('admin.projects.index')
                    ->with('page', 'projects')
                    ->with('sub_page', 'projects-view')
                    ->with('title',$title)
                    ->with('projects', $projects);
    
    }

    /**
     * @method projects_create()
     *
     * @uses display create static page 
     *
     * @created Vithya R
     *
     * @updated    
     *
     * @param
     *
     * @return view page   
     *
     */
    public function projects_create() {

        $project = new Project;

        $users = User::Approved()->get();

        return view('admin.projects.create')
                ->with('page', 'projects')
                ->with('sub_page', 'projects-create')
                ->with('users', $users)
                ->with('project', $project);
    }

    /**
     * @method projects_edit()
     *
     * @uses To display and update project details based on the project id
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param object $request - project Id
     * 
     * @return redirect view page 
     *
     */
    public function projects_edit(Request $request) {

        try {

            $project = Project::find($request->project_id);

            if(!$project) {

                throw new Exception(tr('project_not_found'), 101);
            }

            $users = User::Approved()->get();

            $users = selected($users, $project->user_id, 'id');

            return view('admin.projects.edit')
                    ->with('page', 'projects')
                    ->with('sub_page', 'projects-view')
                    ->with('users', $users)
                    ->with('project', $project);

        } catch(Exception $e) {

            return redirect()->route('admin.projects.index')->with('flash_error', $e->getMessage());

        }
    }

    /**
     * @method projects_save()
     *
     * @uses To save the page details of new/existing page object based on details
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param
     *
     * @return index page    
     *
     */
    public function projects_save(Request $request) {

        try {

            $timezone = Auth::guard('admin')->user()->timezone ?? 'Asia/Kolkata';

            DB::beginTransaction();

            $rules = [
                // 'user_id' => 'required',
                'name' =>  !$request->project_id ? 'required|max:191|unique:projects,name' : 'required',
                'name' => 'required',
                'description' => 'required',
                'picture' => 'mimes:jpeg,jpg,png',
                'token_symbol' => 'required',
                'total_tokens' => 'required',
                'allowed_tokens' => 'required|lte:total_tokens',
                'exchange_rate' => 'required',
                'website' => '',
                'twitter_link' => '',
                'facebook_link' => '',
                'telegram_link' => '',
                'medium_link' => '',
                'start_time' => 'required|after:now',
                'end_time' => 'required|after:start_time',
                'next_round_start_time' => 'required|numeric',
                'from_wallet_address' => 'required',
                // 'access_type' => 'required',
            ]; 
                        
            Helper::custom_validator($request->all(), $rules);


            $project = Project::find($request->project_id) ?? new Project;

            $project->user_id = $request->user_id ?? 0;

            $project->name = $request->name ?: $project->name;

            $project->description = $request->description ?: $project->description;

            $project->from_wallet_address = $request->from_wallet_address ?: "";

            $project->access_type = $request->access_type ?? "";

            $project->total_tokens = $request->total_tokens ?: $project->total_tokens;

            $project->exchange_rate = $request->exchange_rate ?: $project->exchange_rate;

            $project->allowed_tokens = $request->allowed_tokens ?: $project->allowed_tokens;

            $project->token_symbol = $request->token_symbol ?: $project->token_symbol;

            $project->website = $request->website ?: "";

            $project->twitter_link = $request->twitter_link ?: "";

            $project->facebook_link = $request->facebook_link ?: "";

            $project->telegram_link = $request->telegram_link ?: "";

            $project->medium_link = $request->medium_link ?: "";

            $project->contract_address = $request->contract_address ?: "";

            $project->decimal_points = $request->decimal_points ?: "";

            $project->uploaded_by = ADMIN;

            $project->status = APPROVED;
            
            $project->start_time = $start_time = $request->start_time ? common_server_date($request->start_time, $timezone, 'Y-m-d H:i:s') : ($project->start_time ?? date("Y-m-d H:i:s"));

            $project->end_time = $end_time = $request->end_time ? common_server_date($request->end_time, $timezone, 'Y-m-d H:i:s') : ($project->end_time ?? date("Y-m-d H:i:s"));

            $project->next_round_start_time = $request->next_round_start_time ?: 0;

            $current_timestamp = common_server_date(date("Y-m-d H:i:s"), "", 'Y-m-d H:i:s');

            $scheduled_date = strtotime(date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($current_timestamp))));

            $project->publish_status = ($project->publish_status == PROJECT_PUBLISH_STATUS_SCHEDULED) ? PROJECT_PUBLISH_STATUS_INITIATED : ($project->publish_status ?? PROJECT_PUBLISH_STATUS_INITIATED);

            if ($scheduled_date < strtotime($start_time)) {
                
                $project->publish_status = PROJECT_PUBLISH_STATUS_SCHEDULED;
            }

            if($request->hasFile('picture')) {

                if($request->project_id) {

                    Helper::storage_delete_file($project->picture, PROJECTS_PATH); 
                    // Delete the old pic
                }

                $project->picture = Helper::storage_upload_file($request->file('picture'), PROJECTS_PATH);
            }

            if($project->save()) {

                $message = $request->project_id ? tr('project_updated_success') : tr('project_created_success');

                if(!$request->project_id) {

                    // $user = User::find($project->user_id);

                    // JobRepo::admin_projects_create($user,$project);

                }

                DB::commit();
                
                return redirect()->route('admin.projects.view', ['project_id' => $project->id] )->with('flash_success', $message);

            } 

            throw new Exception(tr('project_save_failed'), 101);
                      
        } catch(Exception $e) {

            DB::rollback();

            return back()->withInput()->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method projects_delete()
     *
     * Used to view file of the create the static page 
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param -
     *
     * @return view page   
     */

    public function projects_delete(Request $request) {

        try {

            DB::beginTransaction();

            $project = Project::find($request->project_id);

            if(!$project) {

                throw new Exception(tr('project_not_found'), 101);
                
            }

            $user = User::find($project->user_id);

            JobRepo::admin_projects_delete($user,$project);

            if($project->delete()) {

                DB::commit();

                return redirect()->route('admin.projects.index',['page'=>$request->page])->with('flash_success', tr('project_deleted_success')); 

            } 

            throw new Exception(tr('project_error'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.projects.index')->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method projects_view()
     *
     * @uses view the projects details based on projects id
     *
     * @created Vithya R 
     *
     * @updated 
     *
     * @param object $request
     * 
     * @return View page
     *
     */
    public function projects_view(Request $request) {

        $project = Project::find($request->project_id);

        if(!$project) {
           
            return redirect()->route('admin.projects.index')->with('flash_error',tr('project_not_found'));

        }

        $project_stacks = \App\Models\ProjectStack::where('project_id', $request->project_id)->paginate(12);

        return view("admin.projects.web3view")
                    ->with('page', 'projects')
                    ->with('sub_page', 'projects-view')
                    ->with('project_stacks', $project_stacks)
                    ->with('project', $project);
    }


    /**
     * @method projects_view_for_web()
     *
     * @uses view the projects details based on projects id
     *
     * @created Vithya R 
     *
     * @updated 
     *
     * @param object $request
     * 
     * @return View page
     *
     */
    public function projects_view_for_web(Request $request) {

        $project = Project::find($request->project_id);

        if(!$project) {
           
            return redirect()->route('admin.projects.index')->with('flash_error',tr('project_not_found'));

        }
        
        // $project_payment = ProjectOwnerTransaction::where('project_id', $request->project_id)->first() ?? [];

        // $page = $project_payment ? "admin.projects.web3view" : "admin.projects.view";

        $project_stacks = \App\Models\ProjectStack::where('project_id', $request->project_id)->paginate(12);

        return view("admin.projects.web3view")
                    ->with('page', 'projects')
                    ->with('sub_page', 'projects-view')
                    ->with('project', $project)
                    ->with('project_stacks', $project_stacks);
    }

    /**
     * @method projects_status()
     *
     * @uses To update project status as DECLINED/APPROVED based on project id
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param - integer project_id
     *
     * @return view page 
     */

    public function projects_status(Request $request) {

        try {

            DB::beginTransaction();

            $project = Project::find($request->project_id);

            if(!$project) {

                throw new Exception(tr('project_not_found'), 101);
                
            }

            $project->status = $project->status == DECLINED ? APPROVED : DECLINED;

            $project->save();
            
            $user = User::find($project->user_id);

            JobRepo::projects_status($user,$project);

            // check the project publish status 

            if(in_array($project->publish_status, [PROJECT_PUBLISH_STATUS_INITIATED, PROJECT_PUBLISH_STATUS_SCHEDULED])) {

                $project->publish_status = $project->status == APPROVED ? PROJECT_PUBLISH_STATUS_SCHEDULED : PROJECT_PUBLISH_STATUS_INITIATED;

                $project->save();
            }

            DB::commit();

            $message = $project->status == DECLINED ? tr('project_decline_success') : tr('project_approve_success');

            return redirect()->back()->with('flash_success', $message);

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method projects_publish_status()
     *
     * @uses To update project status as DECLINED/APPROVED based on project id
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param - integer project_id
     *
     * @return view page 
     */

    public function projects_publish_status(Request $request) {

        try {

            DB::beginTransaction();

            $project = Project::find($request->project_id);

            if(!$project) {

                throw new Exception(tr('project_not_found'), 101);
                
            }

            if(!$project->pool_contract_address) {

                throw new Exception(tr('pool_contract_address_not_found'), 101);
                
            }

            $project->publish_status = $request->publish_status;

            $project->start_time = date("Y-m-d H:i:s");

            if($request->publish_status == PROJECT_PUBLISH_STATUS_OPENED) {

                $project->status = APPROVED;
            }

            $project->save();

            $user = User::find($project->user_id);

            JobRepo::projects_publish_status($user,$project);

            DB::commit();

            $message = tr('project_status_updated');

            return redirect()->back()->with('flash_success', $message);

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method project_payments_index()
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

    public function project_payments_index(Request $request) {

        $base_query = ProjectOwnerTransaction::orderBy('created_at','desc');

        if($request->project_id) {

            $base_query = $base_query->where('project_id',$request->project_id);
        }

        if($request->search_key) {

            $search_key = $request->search_key;

            $base_query = $base_query
                ->whereHas('user',function($query) use($search_key){

                    return $query->where('users.name','LIKE','%'.$search_key.'%');
                                
                })->orWhere('project_owner_transactions.payment_id','LIKE','%'.$search_key.'%');
        }


        if($request->today_revenue){

            $base_query = $base_query->whereDate('project_payments.created_at', Carbon::today());

        }

        $project_payments = $base_query->paginate(10);
       
        return view('admin.revenues.project_payments.index')
                ->with('page','payments')
                ->with('sub_page','project-payments')
                ->with('project_payments',$project_payments);
    }


    /**
     * @method project_payments_view()
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

    public function project_payments_view(Request $request) {

        try {

            $project_payment = ProjectPayment::where('id',$request->project_payment_id)->first();
           
            if(!$project_payment) {

                throw new Exception(tr('project_payment_not_found'), 1);
                
            }
           
            return view('admin.revenues.project_payments.view')
                    ->with('page','payments')
                    ->with('sub_page','project-payments')
                    ->with('project_payment',$project_payment);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    }

    /**
     * @method projects_pool_contract_save()
     *
     * @uses Display the subscription payment details for the users
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function projects_pool_contract_save(Request $request) {

        try {

            $project = Project::where('id',$request->project_id)->first();
           
            if(!$project) {

                throw new Exception(tr('project_not_found'), 1);
                
            }

            $project->pool_contract_address = $request->pool_contract_address;

            $project->save();
           
            return response()->json(['success' => true]);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method projects_burn_access_update()
     *
     * @uses Update the burn access status
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function projects_burn_access_update(Request $request) {

        try {

            $project = Project::where('id',$request->project_id)->first();
           
            if(!$project) {

                throw new Exception(tr('project_not_found'), 1);
                
            }

            $project->admin_burn_access = $project->admin_mint_access = ACCESS_GRANTED;

            $project->save();
           
            return response()->json(['success' => true]);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method projects_mint_access_update()
     *
     * @uses Update the burn access status
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function projects_mint_access_update(Request $request) {

        try {

            $project = Project::where('id',$request->project_id)->first();
           
            if(!$project) {

                throw new Exception(tr('project_not_found'), 1);
                
            }

            $project->admin_burn_access = $project->admin_mint_access = $request->status;

            $project->save();
           
            return response()->json(['success' => true]);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method projects_investors_settlement_status()
     *
     * @uses Update the burn access status
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function projects_investors_settlement_status(Request $request) {

        try {

            $project = Project::where('id',$request->project_id)->first();
           
            if(!$project) {

                throw new Exception(tr('project_not_found'), 1);
                
            }

            $project->investors_settlement_status = YES;

            $project->save();
           
            return response()->json(['success' => true]);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method project_owner_settlement_status()
     *
     * @uses Update the burn access status
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function project_owner_settlement_status(Request $request) {

        try {

            $project = Project::where('id',$request->project_id)->first();
           
            if(!$project) {

                throw new Exception(tr('project_not_found'), 1);
                
            }

            $project->project_owner_settlement_status = YES;

            $project->save();
           
            return response()->json(['success' => true]);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method projects_revoke_access()
     *
     * @uses Revoke the contract access from the project
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     */

    public function projects_revoke_access(Request $request) {

        try {

            $project = Project::where('id',$request->project_id)->first();
           
            if(!$project) {

                throw new Exception(tr('project_not_found'), 1);
                
            }

            if($request->type == 'BURNER_ROLE') {

                $project->admin_burn_access = ACCESS_REVOKED;
            }

            if($request->type == 'MINTER_ROLE') {

                $project->admin_mint_access = ACCESS_REVOKED;
            }

            if($request->type == 'MINTER_BURNER_ROLE') {

                $project->admin_burn_access = ACCESS_REVOKED;

                $project->admin_mint_access = ACCESS_REVOKED;
            }

            $project->save();
           
            return response()->json(['success' => true]);

        } catch(Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

}
