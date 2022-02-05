<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB, Hash, Setting, Validator, Exception, Enveditor, Log;

use App\Repositories\ProjectRepository as ProjectRepo;

use App\Helpers\Helper;

use App\Models\User;

use App\Models\Project;

use App\Models\InvestedProject;

use App\Models\TokenPayment;

use App\Models\ProjectStack;

use App\Models\ProjectOwnerTransaction;

use App\Repositories\NotificationJobRepo as JobRepo;

class UserCryptoApiController extends Controller
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
     * @method projects()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects(Request $request) {

        try {

            $opened_projects = Project::Opened()->orderBy('projects.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $opened_projects = ProjectRepo::projects_list_response($opened_projects, $request);

            $upcoming_projects = Project::Upcoming()->orderBy('projects.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $upcoming_projects = ProjectRepo::projects_list_response($upcoming_projects, $request);

            $closed_projects = Project::Closed()->orderBy('projects.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $closed_projects = ProjectRepo::projects_list_response($closed_projects, $request);

            $data['opened_projects'] = $opened_projects;

            $data['upcoming_projects'] = $upcoming_projects;

            $data['closed_projects'] = $closed_projects;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_view()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_view(Request $request) {

        try {

            $rules = ['project_unique_id' => 'required|exists:projects,unique_id'];

            $custom_errors = ['project_unique_id' => api_error(302)];

            Helper::custom_validator($request->all(), $rules, $custom_errors);

            $project = Project::firstWhere('projects.unique_id', $request->project_unique_id);

            $project = ProjectRepo::projects_single_response($project, $request);

            $data['project'] = $project;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method opened_projects()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function opened_projects(Request $request) {

        try {

            $opened_projects = Project::Opened()->orderBy('projects.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $opened_projects = ProjectRepo::projects_list_response($opened_projects, $request);

            $data['opened_projects'] = $opened_projects;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method upcoming_projects()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function upcoming_projects(Request $request) {

        try {

            $upcoming_projects = Project::Upcoming()->orderBy('projects.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $upcoming_projects = ProjectRepo::projects_list_response($upcoming_projects, $request);

            $data['upcoming_projects'] = $upcoming_projects;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }


    /**
     * @method closed_projects()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function closed_projects(Request $request) {

        try {

            $closed_projects = Project::Closed()->orderBy('projects.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $closed_projects = ProjectRepo::projects_list_response($closed_projects, $request);

            $data['closed_projects'] = $closed_projects;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_index_for_owner()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_index_for_owner(Request $request) {

        try {

            $base_query = $total_query = Project::where('projects.user_id', $request->id);

            if($request->type == 'upcoming') {

                $base_query = $base_query->Upcoming();

            } elseif($request->type == 'closed') {

                $base_query = $base_query->Closed();

            } elseif($request->type == 'opened') {

                $base_query = $base_query->Opened();
            }

            $projects = $base_query->orderBy('projects.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $projects = ProjectRepo::projects_list_response($projects, $request);

            $data['projects'] = $projects;

            $data['total_projects'] = $total_query->count() ?: 0;

            $data['user_type'] = $this->loginUser->user_type ?? NO;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_view_for_owner()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_view_for_owner(Request $request) {

        try {

            $rules = ['project_unique_id' => 'required|exists:projects,unique_id'];

            $custom_errors = ['project_unique_id' => api_error(302)];

            Helper::custom_validator($request->all(), $rules, $custom_errors);

            $project = Project::with('projectOwnerTransaction')->firstWhere('projects.unique_id', $request->project_unique_id);

            $project = ProjectRepo::projects_single_response($project, $request);

            $data['project'] = $project;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_save()
     *
     * @uses Project save the details
     * 
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param Form data
     *
     * @return Json response with user details
     */
    public function projects_save(Request $request) {

        try {

            // Check the subscription allowed projects

            if(!$request->project_id) {

                if($this->loginUser->remaining_projects <= 0 ) {

                    throw new Exception(api_error(308), 308);

                }
            }

            DB::beginTransaction();

            $today = common_date(date('Y-m-d H:i:s'), $request->timezone);

            $rules = [
                'name' => 'required',
                'description' => 'required',
                'picture' => $request->id ? '' : 'nullable|mimes:jpeg,jpg,png',
                'token_symbol' => 'required',
                'total_tokens' => 'required',
                'allowed_tokens' => 'required',
                'exchange_rate' => 'required',
                'website' => 'required',
                'start_time' => $request->id ? 'required|date|bail' : 'required|date|after:'.$today.'|bail',
                'end_time' => $request->id ? 'required|date|bail' : 'required|date|after:'.$today.'|bail',
                'contract_address' => 'required',
                'decimal_points' => 'required'
            ];

            Helper::custom_validator($request->all(), $rules);

            $project = Project::find($request->project_id) ?? new Project;

            $project->user_id = $request->id;

            $project->name = $request->name ?? "Project-".rand();

            $project->description = $request->description ?? "";

            $project->token_symbol = $request->filled('token_symbol') ? $request->token_symbol : "";

            $project->total_tokens = $request->filled('total_tokens') ? $request->total_tokens : "";

            $project->allowed_tokens = $request->filled('allowed_tokens') ? $request->allowed_tokens : "";

            $project->exchange_rate = $request->filled('exchange_rate') ? $request->exchange_rate : "";
            $project->decimal_points = $request->filled('decimal_points') ? $request->decimal_points : "";
            $project->contract_address = $request->filled('contract_address') ? $request->contract_address : "";

            $project->website = $request->filled('website') ? $request->website : "";

            $project->twitter_link = $request->filled('twitter_link') ? $request->twitter_link : "";

            $project->facebook_link = $request->filled('facebook_link') ? $request->facebook_link : "";

            $project->telegram_link = $request->filled('telegram_link') ? $request->telegram_link : "";

            $project->medium_link = $request->filled('medium_link') ? $request->medium_link : "";

            $project->start_time = $request->start_time ? common_server_date($request->start_time, $this->timezone, 'Y-m-d H:i:s') : ($project->start_time ?? date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))));

            $project->end_time = $request->end_time ? common_server_date($request->end_time, $this->timezone, 'Y-m-d H:i:s') : ($project->end_time ?? date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))));

            if(!$project->id) {

                $project->publish_status = PROJECT_PUBLISH_STATUS_INITIATED;

                $project->status = PENDING;

                $project->payment_status = $request->payment_status ?: PAID;
            }

            if($request->hasFile('picture') ) {
                
                Helper::storage_delete_file($project->picture, PROJECTS_PATH); 
                
                $project->picture = Helper::storage_upload_file($request->file('picture'), PROJECTS_PATH);
            }

            $project->save();

            if($project->save()) {

                $data = Project::find($project->id);

                if(!$request->project_id) {

                    JobRepo::user_projects_create($data);

                }

                DB::commit();

                return $this->sendResponse($message = api_success(300), $success_code = 300, $data);

            }   

            throw new Exception(api_error(300), 300);
            
        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method projects_contract_address_update()
     *
     * @uses Project save the details
     * 
     * @created Vithya R 
     *
     * @updated Vithya R
     *
     * @param Form data
     *
     * @return Json response with user details
     */
    public function projects_contract_address_update(Request $request) {

        try {

            DB::beginTransaction();

            $today = common_date(date('Y-m-d H:i:s'), $request->timezone);

            $rules = [
                'project_id' => 'required',
                'pool_contract_address' => 'required',
            ];

            Helper::custom_validator($request->all(), $rules);

            $project = Project::find($request->project_id);

            if(!$project) {
                throw new Exception(api_error(302), 302);
            }

            $project->pool_contract_address = $request->pool_contract_address;

            if($project->save()) {

                $data = Project::find($project->id);

                DB::commit();

                return $this->sendResponse($message = api_success(310), $success_code = 310, $data);

            }   

            throw new Exception(api_error(300), 300);
            
        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method projects_payment_status_update()
     *
     * @uses Project allowed tokens payments status
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_payment_status_update(Request $request) {

        try {

            DB::beginTransaction();

            // Validation start

            $rules = ['project_id' => 'required|exists:projects,id', 'payment_status' => 'required'];

            $custom_errors = ['project_id' => api_error(302)];

            Helper::custom_validator($request->all(), $rules, $custom_errors);

            // Validation end

            $project = Project::where('projects.id', $request->project_id)->where('user_id', $request->id)->first();

            if(!$project) {

                throw new Exception(api_error(302), 302);
            }

            $project->payment_status = $request->payment_status ?: $project->payment_status;

            $project->save();

            DB::commit();

            return $this->sendResponse($message = api_success(307) , $code = 307, $data = $project);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_delete()
     *
     * @uses Delete project
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_delete(Request $request) {

        try {

            DB::beginTransaction();

            // Validation start

            $rules = ['project_id' => 'required|exists:projects,id'];

            $custom_errors = ['project_id' => api_error(302)];

            Helper::custom_validator($request->all(), $rules, $custom_errors);

            // Validation end

            $project = Project::where('projects.id', $request->project_id)->where('user_id', $request->id)->delete();

            DB::commit();

            return $this->sendResponse($message = api_success(302) , $code = 302, $data = []);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method invested_projects()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function invested_projects(Request $request) {

        try {

            // Validation start

            $rules = ['project_id' => 'nullable|exists:projects,id'];

            $custom_errors = ['project_id' => api_error(302)];

            Helper::custom_validator($request->all(), $rules, $custom_errors);

            // Validation end

            $base_query = $total_query = \App\Models\ProjectStack::where('user_id',$request->id)->orderBy('created_at','desc');

            if($request->project_id) {

                $base_query = $base_query->where('project_id', $request->project_id);
            }

            $invested_projects = $base_query->with('project')->skip($this->skip)->take($this->take)->get();

            $data['invested_projects'] = $invested_projects ?? emptyObject();

            $data['total'] = $total_query->count() ?? 0;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method token_payments()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function token_payments(Request $request) {

        try {

            $base_query = $total_query = TokenPayment::where('user_id', $request->id);

            $token_payments = $base_query->orderBy('token_payments.updated_at', 'desc')->skip($this->skip)->take($this->take)->get();

            $data['token_payments'] = $token_payments;

            $data['total'] = $total_query->count() ?? 0;

            return $this->sendResponse($message = '' , $code = '', $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }
    
    /**
     * @method token_payments_save()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function token_payments_save(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'from_wallet_address' => 'required',
                'from_payment_id' => 'required',
                'purchased' => 'required'
            ];

            Helper::custom_validator($request->all(), $rules);

            $token_payment = TokenPayment::firstWhere('from_wallet_address',  $request->from_payment_id) ?? new TokenPayment;

            $token_payment->user_id = $request->id;

            $token_payment->from_wallet_address = $request->from_wallet_address;

            $token_payment->from_payment_id = $request->from_payment_id;

            $token_payment->to_payment_id = $token_payment->to_wallet_address = "";

            $token_payment->purchased = $request->purchased ?? 0.00;

            if($token_payment->save()) {

                $data = TokenPayment::find($token_payment->id);

                DB::commit();

                return $this->sendResponse($message = api_success(303), $success_code = 303, $data);

            }

            throw new Exception(api_error(301), 301);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_investment_save()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_investment_save(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'from_wallet_address' => 'required',
                'from_payment_id' => 'required',
                'purchased' => 'required',
                'project_id' => 'required|exists:projects,id'
            ];

            Helper::custom_validator($request->all(), $rules);

            // Check the project exists

            $project = Project::where('projects.id', $request->project_id)->Opened()->first();

            if(!$project) {
                throw new Exception(api_error(302), 302);
            }

            if($project->user_id == $request->id) {

                if($project->from_wallet_address == $request->from_wallet_address) {

                    throw new Exception(api_error(303), 303);
                }

            }

            if($request->purchased > $project->allowed_tokens) {

                throw new Exception(api_error(304), 304);
            }

            if($project->total_tokens_purchased >= $project->allowed_tokens) {

                throw new Exception(api_error(306), 306);

            }

            // Remaning tokens check

            $remaining_tokens = $project->allowed_tokens - $project->total_tokens_purchased;

            if($request->purchased > $remaining_tokens) {

                throw new Exception(api_error(306), 306);
            }

            $invested_project = InvestedProject::firstWhere('from_payment_id',  $request->from_payment_id) ?? new InvestedProject;

            $invested_project->user_id = $request->id;

            $invested_project->project_id = $request->project_id;

            $invested_project->from_wallet_address = $request->from_wallet_address;

            $invested_project->from_payment_id = $request->from_payment_id;

            $invested_project->to_payment_id = $invested_project->to_wallet_address = "";

            $invested_project->purchased = $request->purchased ?? 0.00;

            $invested_project->claim_payment_status = CLAIM_UNPAID;
            
            if($invested_project->save()) {

                $project->total_tokens_purchased += $request->purchased;

                $project->total_users_participated += 1;

                $project->save();

                $data = InvestedProject::find($invested_project->id);

                DB::commit();

                return $this->sendResponse($message = api_success(303), $success_code = 303, $data);

            }

            throw new Exception(api_error(301), 301);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_investment_claim()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_investment_claim(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'invested_project_id' => 'required|exists:invested_projects,id',
                'claim_wallet_address' => 'required',
                'claim_payment_id' => 'required',
                'project_id' => 'required|exists:projects,id'
            ];

            Helper::custom_validator($request->all(), $rules);

            // Check the project exists

            $project = Project::where('projects.id', $request->project_id)->Opened()->first();

            if(!$project) {
                throw new Exception(api_error(302), 302);
            }

            if($project->user_id == $request->id) {

                throw new Exception(api_error(303), 303);

            }

            $invested_project = InvestedProject::find($request->invested_project_id);

            $invested_project->claim_wallet_address = $request->claim_wallet_address;

            $invested_project->claim_payment_id = $request->claim_payment_id;

            // $invested_project->claim_payment_status = CLAIM_PAID;

            $invested_project->confirmed = $invested_project->purchased ?? 0.00;

            if($invested_project->save()) {

                $data = InvestedProject::find($invested_project->id);

                DB::commit();

                return $this->sendResponse($message = api_success(306), $success_code = 306, $data);

            }

            throw new Exception(api_error(301), 301);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method project_transactions_save()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function project_transactions_save(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'from_wallet_address' => 'required',
                'from_payment_id' => 'required',
                'total' => 'required',
                'project_id' => 'required|exists:projects,id',
            ];

            Helper::custom_validator($request->all(), $rules);

            // Check the project exists

            $project = Project::where('projects.id', $request->project_id)->where('projects.user_id', $request->id)->first();

            if(!$project) {
                throw new Exception(api_error(302), 302);
            }

            $project_transaction = ProjectOwnerTransaction::firstWhere('project_id',  $request->project_id) ?? new ProjectOwnerTransaction;

            $project_transaction->user_id = $request->id;

            $project_transaction->project_id = $request->project_id;

            $project_transaction->from_wallet_address = $request->from_wallet_address;

            $project_transaction->from_payment_id = $request->from_payment_id;

            $project_transaction->to_payment_id = $project_transaction->to_wallet_address = "";

            $project_transaction->total = $request->total ?? $project->allowed_tokens;

            if($project_transaction->save()) {

                $data = ProjectOwnerTransaction::find($project_transaction->id);

                DB::commit();

                return $this->sendResponse($message = api_success(304), $success_code = 304, $data);

            }

            throw new Exception(api_error(305), 305);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method projects_investment_token_validate()
     *
     * @uses 
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function projects_investment_token_validate(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'valid_token' => 'required',
            ];

            Helper::custom_validator($request->all(), $rules);

            $invested_project = InvestedProject::where('claim_token', $request->valid_token)->first();

            if(!$invested_project) {
                
                throw new Exception(api_error(307), 307);
            }

            $project = Project::where('projects.id', $invested_project->project_id)->Opened()->first();

            if(!$project) {
                throw new Exception(api_error(302), 302);
            }


            if($invested_project->user_id == $request->id) {

                throw new Exception(api_error(303), 303);

            }

            if($invested_project->claim_token != $request->valid_token) {

                // throw new Exception(api_error(307), 307);

            }

            // $invested_project->claim_token = "";

            if($invested_project->save()) {

                $data = new \stdClass();

                $data->invested_project_id = $invested_project->invested_project_id;

                $data->project_id = $invested_project->project_id;

                $data->wallet_address = $invested_project->from_wallet_address ?? '';

                $data->no_of_tokens = $invested_project->purchased ?? 0;

                $data->contract_address = $project->contract_address ?? '';

                $data->decimal_points = $project->decimal_points ?? '';

                DB::commit();

                return $this->sendResponse($message = api_success(305), $success_code = 305, $data);

            }

            throw new Exception(api_error(301), 301);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method project_stacking_save()
     *
     * @uses used to staore the stacking amount
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function project_stacking_save(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'project_id' => 'required|exists:projects,id',
                'wallet_address' => 'required',
                'amount' => 'required|min:1',
                'transaction_id' => 'required',

            ];

            Helper::custom_validator($request->all(), $rules);

            $project = Project::where('projects.id', $request->project_id)->Opened()->first();

            if(!$project) {
                throw new Exception(api_error(302), 302);
            }

            if($project->user_id == $request->id) {

                if($project->from_wallet_address == $request->wallet_address) {

                    throw new Exception(api_error(303), 303);
                }

            }

            if(!$project) {
                
                throw new Exception(api_error(307), 307);
            }

            $is_new_stack = NO;

            $project_stack = ProjectStack::where('project_id', $request->project_id)->where('wallet_address', $request->wallet_address)->first();

            if(!$project_stack) {

                $project_stack = new ProjectStack;

                $is_new_stack = YES;

            }

            $project_stack->user_id = $request->id;

            $project_stack->project_id = $request->project_id;

            $project_stack->wallet_address = $request->wallet_address;

            $project_stack->transaction_id = $request->transaction_id;

            $stacked_amount = $project_stack->stacked + $request->amount;

            $project_stack->stacked = exep_number_format($stacked_amount);

            $total_tokens_purchased = $project->total_tokens_purchased + $request->amount;

            $project->total_tokens_purchased = exep_number_format($total_tokens_purchased);

            if($project_stack->save()) {

                if($is_new_stack == YES) {
                    
                    $project->total_users_participated += 1;
                }

                $project->save();

                $data = $project_stack;

                DB::commit();

                return $this->sendResponse($message = api_success(309), $success_code = 309, $data);

            }

            throw new Exception(api_error(310), 310);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

    /**
     * @method project_unstacking_save()
     *
     * @uses used to staore the stacking amount
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param request id
     *
     * @return JSON Response
     */
    public function project_unstacking_save(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'project_id' => 'required|exists:projects,id',
                'wallet_address' => 'required',
                'amount' => 'required|min:1',
                'transaction_id' => 'required',

            ];

            Helper::custom_validator($request->all(), $rules);

            $project = Project::where('projects.id', $request->project_id)->Opened()->first();

            if(!$project) {
                throw new Exception(api_error(302), 302);
            }

            if($project->user_id == $request->id) {

                if($project->from_wallet_address == $request->wallet_address) {

                    throw new Exception(api_error(303), 303);
                }

            }

            if(!$project) {
                
                throw new Exception(api_error(307), 307);
            }

            $project_stack = ProjectStack::where('project_id', $request->project_id)->where('wallet_address', $request->wallet_address)->first() ?? new ProjectStack;

            $project_stack->user_id = $request->id;

            $project_stack->project_id = $request->project_id;

            $project_stack->wallet_address = $request->wallet_address;

            $project_stack->transaction_id = $request->transaction_id;


            $unstacked_amount = $project_stack->unstacked + $request->amount;

            $project_stack->unstacked = exep_number_format($unstacked_amount);
            
            
            $stacked_amount = $project_stack->stacked - $request->amount;

            $project_stack->stacked = exep_number_format($stacked_amount);

            if($project_stack->save()) {

                $total_tokens_purchased = $project->total_tokens_purchased - $request->amount;

                $project->total_tokens_purchased = exep_number_format($total_tokens_purchased);

                $project->save();

                $data = $project_stack;

                DB::commit();

                return $this->sendResponse($message = api_success(309), $success_code = 309, $data);

            }

            throw new Exception(api_error(310), 310);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());
        
        }
    
    }

}
