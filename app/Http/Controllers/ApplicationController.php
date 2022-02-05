<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log, Validator, Exception, DB, Setting;

use App\Helpers\Helper;

use App\Models\TokenPayment, App\Models\SubscriptionPayment, App\Models\InvestedProject, App\Models\ProjectOwnerTransaction;

use App\Models\Project, App\Models\ContactForm;

use App\Repositories\NotificationJobRepo as JobRepo;

class ApplicationController extends Controller
{
    protected $crypto_url;

    public function __construct(Request $request) {

        $this->crypto_url = Setting::get("crypto_url") ?? "https://api.etherscan.io/";

        Log::info(url()->current());
    }
    /**
     * @method settings_generate_json()
     * 
     * @uses
     *
     * @created vidhya R
     *
     * @updated vidhya R
     * 
     * @param 
     *
     * @return No return response.
     *
     */

    public function settings_generate_json(Request $request) {

        try {

            Helper::settings_generate_json();

            return $this->sendResponse("", "", $data = []);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }
    
    }
    
    /**
     * @method faqs_index()
     *
     * @uses to get the faqs
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function faqs_index(Request $request) {

        $base_query = \App\Models\Faq::where('status', APPROVED)->orderBy('faqs.id', 'desc');             
        $faqs = $base_query->get();

        $response = ['success' => true , 'data' => $faqs ?? emptyObject()];

        return response()->json($response , 200);

    }

    /**
     * @method faqs_view()
     *
     * @uses to get the faqs
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function faqs_view(Request $request) {

        $faq = \App\Models\Faq::where('status', APPROVED)->where('faqs.unique_id' , $request->faq_unique_id)->first();
                                
        $response = ['success' => true , 'data' => $faq ?? emptyObject()];

        return response()->json($response , 200);

    }

    /**
     * @method static_pages_index()
     *
     * @uses to get the pages
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function static_pages_index(Request $request) {

        $base_query = \App\Models\StaticPage::where('status', APPROVED)->orderBy('title', 'asc');
                                
        if($request->unique_id) {

            $static_pages = $base_query->where('unique_id' , $request->unique_id)->first();

        } else {

            $static_pages = $base_query->get();

        }

        $response = ['success' => true , 'data' => $static_pages ? $static_pages->toArray(): []];

        return response()->json($response , 200);

    }

    /**
     * @method static_pages_view()
     *
     * @uses to get the pages
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function static_pages_view(Request $request) {

        $static_page = \App\Models\StaticPage::where('status', APPROVED)->where('unique_id' , $request->unique_id)->orderBy('title', 'asc')->first();
                                
        $response = ['success' => true , 'data' => $static_page ?? emptyObject()];

        return response()->json($response , 200);

    }

    /**
     * @method cron_auto_publish_projects()
     *
     * @uses
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function cron_auto_publish_projects(Request $request) {

        try {

            Log::info('Publish Project success');

            DB::begintransaction();

            $current_timestamp = date("Y-m-d H:i:s");

            // Get the pending transaction payments

            $base_query = $total_query = Project::whereNotNull('start_time')->where('publish_status', PROJECT_PUBLISH_STATUS_SCHEDULED)->where('start_time','<', $current_timestamp);

            $projects = $base_query->get();

            $data['total'] = $total_query->count() ?? 0;

            $published = 0;

            foreach($projects as $project) {

                if ($project->pool_contract_address) {
                    
                    $project->publish_status = PROJECT_PUBLISH_STATUS_OPENED;

                    $project->save();

                    // JobRepo::publish_project_cron($project);

                    $published++;
                }
            }

            DB::commit();

            $data['paid'] = $published ?? 0;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method cron_auto_close_projects()
     *
     * @uses
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function cron_auto_close_projects(Request $request) {

        try {

            Log::info('Close Project success');

            DB::begintransaction();

            $current_timestamp = date("Y-m-d H:i:s");

            // Get the pending transaction payments

            $base_query = $total_query = Project::whereNotNull('end_time')->where('publish_status', PROJECT_PUBLISH_STATUS_OPENED)->where('end_time','<', $current_timestamp);

            $projects = $base_query->get();

            $data['total'] = $total_query->count() ?? 0;

            $closed = 0;

            foreach($projects as $project) {

                $project->publish_status = PROJECT_PUBLISH_STATUS_CLOSED;

                $project->save();

                // JobRepo::publish_project_cron($project);

                $closed++;

            }

            DB::commit();

            $data['paid'] = $closed ?? 0;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method cron_subscription_payments_check()
     *
     * @uses
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function cron_subscription_payments_check(Request $request) {

        try {

            DB::begintransaction();

            $apiKey = Setting::get('ether_api_key');

            $httpClient = new \GuzzleHttp\Client();

            // Get the pending transaction payments

            $base_query = $total_query = SubscriptionPayment::whereNotNull('payment_id')->where('payment_mode', CRYPTO)->where('status', UNPAID);

            $pending_subscription_payments = $base_query->get();

            $data['total'] = $total_query->count() ?? 0;

            $paid_payments = 0;

            foreach($pending_subscription_payments as $pending_subscription_payment) {

                $transactionHash = $pending_subscription_payment->payment_id;

                $ether_exec_url = $this->crypto_url."api?module=transaction&action=getstatus&txhash=$transactionHash&apikey=$apiKey";

                $ether_exec_response = $httpClient->get($ether_exec_url);

                $ether_exec_response = json_decode($ether_exec_response->getBody()->getContents());

                $isError = $ether_exec_response->result->isError ?? YES;

                if($isError == NO) {

                    $ether_trans_url = $this->crypto_url."api?module=transaction&action=gettxreceiptstatus&txhash=$transactionHash&apikey=$apiKey";

                    $ether_trans_response = $httpClient->get($ether_trans_url);

                    $ether_trans_response = json_decode($ether_trans_response->getBody()->getContents());

                    $trans_status = $ether_trans_response->result->status ?? UNPAID;

                    if($trans_status == PAID) {

                        $pending_subscription_payment->status = TOKEN_PAYMENT_PAID;

                        $pending_subscription_payment->save();

                        $paid_payments++;

                        JobRepo::cron_subscription_payment($pending_subscription_payment);

                    }
                }

                sleep(3);

            }

            DB::commit();

            $data['paid'] = $paid_payments ?? 0;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method cron_token_payments_check()
     *
     * @uses
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function cron_token_payments_check(Request $request) {

        try {

            DB::begintransaction();

            $apiKey = Setting::get('ether_api_key');

            $httpClient = new \GuzzleHttp\Client();

            // Get the pending transaction payments

            $base_query = $total_query = TokenPayment::where('status', PENDING);

            $pending_token_payments = $base_query->get();

            $data['total'] = $total_query->count() ?? 0;

            $paid_payments = 0;

            foreach($pending_token_payments as $pending_token_payment) {

                $ether_exec_url = $this->crypto_url."api?module=transaction&action=getstatus&txhash=0x27e607aa3412d47df379f89e9df40f03ce175b5f22cebf224fd4aee260c8ca32&apikey=$apiKey";

                $ether_exec_response = $httpClient->get($ether_exec_url);

                $ether_exec_response = json_decode($ether_exec_response->getBody()->getContents());

                $isError = $ether_exec_response->result->isError ?? YES;

                if($isError == NO) {

                    $ether_trans_url = $this->crypto_url."api?module=transaction&action=gettxreceiptstatus&txhash=0x27e607aa3412d47df379f89e9df40f03ce175b5f22cebf224fd4aee260c8ca32&apikey=$apiKey";

                    $ether_trans_response = $httpClient->get($ether_trans_url);

                    $ether_trans_response = json_decode($ether_trans_response->getBody()->getContents());

                    $trans_status = $ether_trans_response->result->status ?? UNPAID;

                    if($trans_status == PAID) {

                        $pending_token_payment->status = TOKEN_PAYMENT_PAID;

                        $pending_token_payment->save();

                        $paid_payments++;

                    }
                }

                sleep(3);

            }

            DB::commit();

            $data['paid'] = $paid_payments ?? 0;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method cron_investment_payments_check()
     *
     * @uses
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function cron_investment_payments_check(Request $request) {

        try {

            DB::begintransaction();

            $apiKey = Setting::get('ether_api_key');

            $httpClient = new \GuzzleHttp\Client();

            // Get the pending transaction payments

            $base_query = $total_query = InvestedProject::whereNotNull('from_payment_id')->where('status', UNPAID);

            $invested_projects = $base_query->get();

            $data['total'] = $total_query->count() ?? 0;

            $paid_payments = 0;

            foreach($invested_projects as $invested_project) {

                $transactionHash = $invested_project->from_payment_id;

                $ether_exec_url = $this->crypto_url."api?module=transaction&action=getstatus&txhash=$transactionHash&apikey=$apiKey";

                $ether_exec_response = $httpClient->get($ether_exec_url);

                $ether_exec_response = json_decode($ether_exec_response->getBody()->getContents());

                $isError = $ether_exec_response->result->isError ?? YES;

                if($isError == NO) {

                    $ether_trans_url = $this->crypto_url."api?module=transaction&action=gettxreceiptstatus&txhash=$transactionHash&apikey=$apiKey";

                    $ether_trans_response = $httpClient->get($ether_trans_url);

                    $ether_trans_response = json_decode($ether_trans_response->getBody()->getContents());

                    $trans_status = $ether_trans_response->result->status ?? UNPAID;

                    if($trans_status == PAID) {

                        $invested_project->status = TOKEN_PAYMENT_PAID;

                        $invested_project->save();

                        $paid_payments++;

                        JobRepo::invested_project_token_payment($invested_project);

                    }
                }

                sleep(3);

            }

            DB::commit();

            $data['paid'] = $paid_payments ?? 0;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    

    }

    /**
     * @method cron_project_owner_transactions_check()
     *
     * @uses
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function cron_project_owner_transactions_check(Request $request) {

        try {

            DB::begintransaction();

            $apiKey = Setting::get('ether_api_key');

            $httpClient = new \GuzzleHttp\Client();

            // Get the pending transaction payments

            $base_query = $total_query = ProjectOwnerTransaction::whereNotNull('from_payment_id')->where('status', UNPAID);

            $project_transactions = $base_query->get();

            $data['total'] = $total_query->count() ?? 0;

            $paid_payments = 0;

            foreach($project_transactions as $project_transaction) {

                $transactionHash = $project_transaction->from_payment_id;

                $ether_exec_url = $this->crypto_url."api?module=transaction&action=getstatus&txhash=$transactionHash&apikey=$apiKey";

                $ether_exec_response = $httpClient->get($ether_exec_url);

                $ether_exec_response = json_decode($ether_exec_response->getBody()->getContents());

                $isError = $ether_exec_response->result->isError ?? YES;

                if($isError == NO) {

                    $ether_trans_url = $this->crypto_url."api?module=transaction&action=gettxreceiptstatus&txhash=$transactionHash&apikey=$apiKey";

                    $ether_trans_response = $httpClient->get($ether_trans_url);

                    $ether_trans_response = json_decode($ether_trans_response->getBody()->getContents());

                    $trans_status = $ether_trans_response->result->status ?? UNPAID;

                    if($trans_status == PAID) {

                        $project_transaction->status = PAID;

                        $project_transaction->save();

                        $paid_payments++;

                        JobRepo::project_transaction_status($project_transaction);

                    }
                }

                sleep(3);

            }

            DB::commit();

            $data['paid'] = $paid_payments ?? 0;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method cron_projects_investment_claim_check()
     *
     * @uses
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function cron_projects_investment_claim_check(Request $request) {

        try {

            DB::begintransaction();

            $apiKey = Setting::get('ether_api_key');

            $httpClient = new \GuzzleHttp\Client();

            // Get the pending transaction payments

            $base_query = $total_query = InvestedProject::whereNotNull('claim_payment_id')->where('status', UNPAID);

            $invested_projects = $base_query->get();

            $data['total'] = $total_query->count() ?? 0;

            $paid_payments = 0;

            foreach($invested_projects as $invested_project) {

                $transactionHash = $invested_project->claim_payment_id;

                $ether_exec_url = $this->crypto_url."api?module=transaction&action=getstatus&txhash=$transactionHash&apikey=$apiKey";

                Log::info("ether_exec_url".$ether_exec_url);

                $ether_exec_response = $httpClient->get($ether_exec_url);

                $ether_exec_response = json_decode($ether_exec_response->getBody()->getContents());

                $isError = $ether_exec_response->result->isError ?? YES;

                if($isError == NO) {

                    $ether_trans_url = $this->crypto_url."api?module=transaction&action=gettxreceiptstatus&txhash=$transactionHash&apikey=$apiKey";

                    Log::info("ether_trans_url".$ether_trans_url);

                    $ether_trans_response = $httpClient->get($ether_trans_url);

                    $ether_trans_response = json_decode($ether_trans_response->getBody()->getContents());

                    $trans_status = $ether_trans_response->result->status ?? UNPAID;

                    if($trans_status == PAID) {

                        $invested_project->claim_payment_status = PAID;

                        $invested_project->save();

                        $paid_payments++;

                        JobRepo::invested_projects_claim($invested_project);

                    }
                }

                sleep(3);

            }

            DB::commit();

            $data['paid'] = $paid_payments ?? 0;

            return $this->sendResponse($message = "", $success_code = "", $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }

    /**
     * @method contact_form_save()
     *
     * @uses
     *
     * @created Arun 
     *
     * @edited 
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function contact_form_save(Request $request) {

        try {

            DB::begintransaction();

            // Validation start

            $rules = [
                'title' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'mobile' => 'nullable|numeric',
                'telegram_link' => 'nullable',
            ];

            Helper::custom_validator($request->all(), $rules, $custom_errors = []);

            // Get the pending transaction payments

            $contact_form = new ContactForm;

            $contact_form->title = $request->title ?? "";

            $contact_form->name = $request->name ?? "";

            $contact_form->email = $request->email ?? "";

            $contact_form->mobile = $request->mobile ?? "";

            $contact_form->description = $request->description ?? "";

            $contact_form->telegram_link = $request->telegram_link ?? "";

            $contact_form->status = CONTACT_FORM_INITIATED;

            if ($contact_form->save()) {
                
                JobRepo::contact_form_save($contact_form);
            }

            DB::commit();

            $data['contact_form'] = $contact_form ?? 0;

            return $this->sendResponse($message = api_success(311), $success_code = 311, $data);

        } catch(Exception $e) {

            DB::rollback();

            return $this->sendError($e->getMessage(), $e->getCode());

        }

    }



}
