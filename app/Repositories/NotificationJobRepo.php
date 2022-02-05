<?php

namespace App\Repositories;

use App\Helpers\Helper;

use Log, Validator, Setting, Exception, DB;

use App\Models\User;

class NotificationJobRepo {

    /**
     * @method user_register_job()
     *
     * @uses User register account
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function user_register_job($user) {

        try {

            if (Setting::get('is_email_notification') == YES && $user) {

                $email_data['subject'] = tr('user_welcome_title').' '.Setting::get('site_name');

                $email_data['page'] = "emails.users.welcome";

                $email_data['data'] = $user;

                $email_data['email'] = $user->email;

                $email_data['name'] = $user->name;

                $email_data['verification_code'] = $user->verification_code;

                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method user_register_job()
     *
     * @uses User register account
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function user_forgot_password($user,$token) {

        try {

            if (Setting::get('is_email_notification') == YES && $user) {

                $email_data['subject'] = tr('reset_password_title' , Setting::get('site_name'));

                $email_data['email']  = $user->email;

                $email_data['name']  = $user->name;

                $email_data['page'] = "emails.users.forgot-password";

                $email_data['url'] = Setting::get('frontend_url')."reset-password/".$token;
                
                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method user_register_job()
     *
     * @uses User register account
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function user_change_password($user) {

        try {

            if (Setting::get('is_email_notification') == YES && $user) {

                $email_data['subject'] = tr('change_password_email_title' , Setting::get('site_name'));

                $email_data['email']  = $user->email;

                $email_data['page'] = "emails.users.change-password";

                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method admin_projects_delete()
     *
     * @uses Project Delete Email to User
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function admin_projects_delete($user,$project) {

        try {

            if (Setting::get('is_email_notification') == YES && $user) {

                $email_data['subject'] = tr('user_project_deleted_by_admin').' '.Setting::get('site_name');

                $email_data['page'] = "emails.projects.delete";

                $email_data['user'] = $user;

                $email_data['project'] = $project;

                $email_data['email'] = $user->email;

                $email_data['name'] = $user->name;

                dispatch(new \App\Jobs\SendEmailJob($email_data));
            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method admin_projects_delete()
     *
     * @uses Project Delete Email to User
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function projects_publish_status($user,$project) {

        try {

            if (Setting::get('is_email_notification') == YES && $user) {

                $email_data['subject'] = project_publish_status_formatted($project->publish_status).' '.Setting::get('site_name');

                $email_data['page'] = "emails.projects.publish_status";

                $email_data['user'] = $user;

                $email_data['project'] = $project;

                $email_data['email'] = $user->email;

                $email_data['name'] = $user->name;

                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method projects_status()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function projects_status($user,$project) {

        try {

            if (Setting::get('is_email_notification') == YES && $user) {

                $subject = $project->status == APPROVED ? tr('project_approved_by_admin') : tr('project_declined_by_admin');

                $email_data['subject'] = Setting::get('site_name').' '.$subject;

                $email_data['page'] = "emails.projects.status";

                $email_data['user'] = $user;

                $email_data['project'] = $project;

                $email_data['email'] = $user->email;

                $email_data['name'] = $user->name;

                $email_data['status'] = $subject;

                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method admin_projects_create()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function admin_projects_create($user,$project) {

        try {

            if (Setting::get('is_email_notification') == YES && $user) {

                $email_data['subject'] = Setting::get('site_name').'-'.tr('project_created_by_admin');

                $email_data['page'] = "emails.projects.project";

                $email_data['user'] = $user;

                $email_data['project'] = $project;

                $email_data['email'] = $user->email;

                $email_data['name'] = $user->name;

                dispatch(new \App\Jobs\SendEmailJob($email_data));

            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method admin_projects_create()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function user_projects_create($project) {

        try {

            $job_data['project'] = $project;

            dispatch(new \App\Jobs\ProjectCreateJob($job_data));

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method publish_project_job()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function publish_project_cron($project) {

        try {

            $job_data['project'] = $project;

            dispatch(new \App\Jobs\ProjectPublishJob($job_data));

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method publish_project_job()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function cron_subscription_payment($subscription_payment) {

        try {

            $job_data['subscription_payment'] = $subscription_payment;

            dispatch(new \App\Jobs\SubscriptionPaymentJob($job_data));

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method publish_project_job()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function invested_project_token_payment($invested_project) {

        try {

            $job_data['invested_project'] = $invested_project;

            dispatch(new \App\Jobs\TokenPaymentJob($job_data));

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method publish_project_job()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function project_transaction_status($project_transaction) {

        try {

            $job_data['project_transaction'] = $project_transaction;

            dispatch(new \App\Jobs\ProjectTransactionJob($job_data));

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

    /**
     * @method invested_projects_claim()
     *
     * @uses
     *
     * @created
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function invested_projects_claim($invested_project) {

        try {

            $job_data['invested_project'] = $invested_project ?? [];

            if($job_data['invested_project']) {

                dispatch(new \App\Jobs\InvestedProjectClaimJob($job_data));
            }

        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }

     /**
     * @method admin_forgot_password_job()
     *
     * @uses Admin forgot password job
     *
     * @created Jeevan
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function admin_forgot_password_job($admin, $token) {

        try { 

            if (Setting::get('is_email_notification') == YES && $admin) {    

                $email_data['subject'] = tr('reset_password_title' , Setting::get('site_name'));
        
                $email_data['email']  = $admin->email;
        
                $email_data['name']  = $admin->name;
        
                $email_data['user']  = $admin;
            
                $email_data['page'] = "emails.admin_forgot_password";
            
                $email_data['url'] = url('/')."/admin/reset-password?token=".$token;
               
                dispatch(new \App\Jobs\SendEmailJob($email_data));
            }
    
        } catch(Exception $e) {

                $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

                return response()->json($response, 200);

        }

    }

    /**
     * @method contact_form_save()
     *
     * @uses Save contanct form
     *
     * @created Jeevan
     * 
     * @updated 
     *
     * @param object $request
     *
     * @return object 
     */

    public static function contact_form_save($contact_form) {

        try { 

            if (Setting::get('is_email_notification') == YES && $contact_form) {    

                $email_data['subject'] = tr('contact_form' , Setting::get('site_name'));
        
                $email_data['email']  = Setting::get('contact_email');
        
                $email_data['name']  = $contact_form->name;
        
                $email_data['contact_form']  = $contact_form;
            
                $email_data['page'] = "emails.contact_form";
               
                dispatch(new \App\Jobs\SendEmailJob($email_data));
            }
    
        } catch(Exception $e) {

            $response = ['success' => false, 'error' => $e->getMessage(), 'error_code' => $e->getCode()];

            return response()->json($response, 200);

        }

    }
    
}