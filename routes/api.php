<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// CRON Jobs

Route::get('cron_token_payments_check', 'ApplicationController@cron_token_payments_check');

Route::get('cron_subscription_payments_check', 'ApplicationController@cron_subscription_payments_check');

Route::get('cron_investment_payments_check', 'ApplicationController@cron_investment_payments_check');

Route::get('cron_project_owner_transactions_check', 'ApplicationController@cron_project_owner_transactions_check');

Route::get('cron_projects_investment_claim_check', 'ApplicationController@cron_projects_investment_claim_check');

Route::get('cron_auto_publish_projects', 'ApplicationController@cron_auto_publish_projects');

Route::get('cron_auto_close_projects', 'ApplicationController@cron_auto_close_projects');

Route::any('faqs_index', 'ApplicationController@faqs_index');

Route::any('faqs_view', 'ApplicationController@faqs_view');


Route::any('static_pages_index', 'ApplicationController@static_pages_index');

Route::any('static_pages_view', 'ApplicationController@static_pages_view');

Route::post('contact_form_save', 'ApplicationController@contact_form_save');

Route::post('regenerate_email_verification_code', 'Api\V1\UserAccountApiController@regenerate_email_verification_code');

Route::post('verify_email', 'Api\V1\UserAccountApiController@verify_email');

Route::any('get_settings_json', function () {

    $settings_folder = storage_path('public/'.SETTINGS_JSON);

    if(\File::isDirectory($settings_folder)){

    } else {

        \File::makeDirectory($settings_folder, 0777, true, true);

        \App\Helpers\Helper::settings_generate_json();
    }

    $jsonString = file_get_contents(storage_path('app/public/'.SETTINGS_JSON));

    $data = json_decode($jsonString, true);

    return $data;

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('username_validation', 'Api\V1\UserAccountApiController@username_validation');

Route::post('register', 'Api\V1\UserAccountApiController@register');

Route::post('login', 'Api\V1\UserAccountApiController@login');

Route::post('profile', 'Api\V1\UserAccountApiController@profile');

Route::post('update_profile', 'Api\V1\UserAccountApiController@update_profile');

Route::post('forgot_password', 'Api\V1\UserAccountApiController@forgot_password');

Route::post('change_password', 'Api\V1\UserAccountApiController@change_password');

Route::post('delete_account', 'Api\V1\UserAccountApiController@delete_account');

Route::post('logout', 'Api\V1\UserAccountApiController@logout');


// Get KYC Documents and verifications api's

// Route::post('documents_list', 'Api\V1\VerificationApiController@documents_list');

// Route::post('documents_save','Api\V1\VerificationApiController@documents_save');

// Route::post('documents_delete','Api\V1\VerificationApiController@documents_delete');

// Route::post('documents_delete_all','Api\V1\VerificationApiController@documents_delete_all');

// Route::post('user_documents_status','Api\V1\VerificationApiController@user_documents_status');

// Projects Start

Route::post('projects', 'Api\V1\UserCryptoApiController@projects');

Route::post('projects_view', 'Api\V1\UserCryptoApiController@projects_view');

Route::post('opened_projects', 'Api\V1\UserCryptoApiController@opened_projects');

Route::post('upcoming_projects', 'Api\V1\UserCryptoApiController@upcoming_projects');

Route::post('closed_projects', 'Api\V1\UserCryptoApiController@closed_projects');

Route::post('projects_view', 'Api\V1\UserCryptoApiController@projects_view');

Route::post('projects_index_for_owner', 'Api\V1\UserCryptoApiController@projects_index_for_owner');

Route::post('projects_view_for_owner', 'Api\V1\UserCryptoApiController@projects_view_for_owner');

Route::post('projects_save', 'Api\V1\UserCryptoApiController@projects_save');

Route::post('projects_payment_status_update', 'Api\V1\UserCryptoApiController@projects_payment_status_update');

Route::post('project_transactions_save', 'Api\V1\UserCryptoApiController@project_transactions_save');

Route::post('projects_delete', 'Api\V1\UserCryptoApiController@projects_delete');

Route::post('projects_status', 'Api\V1\UserCryptoApiController@projects_status');

// Projects end

// Subscriptions start 

Route::post('subscriptions_index','Api\V1\SubscriptionApiController@subscriptions_index');

Route::post('subscriptions_payment_by_card','Api\V1\SubscriptionApiController@subscriptions_payment_by_card');

Route::post('subscriptions_payment_by_crypto','Api\V1\SubscriptionApiController@subscriptions_payment_by_crypto');

Route::post('subscriptions_history','Api\V1\SubscriptionApiController@subscriptions_history');

Route::post('user_project_eligiable_check','Api\V1\UserAccountApiController@user_project_eligiable_check');

Route::post('user_subscription_eligiable_check','Api\V1\SubscriptionApiController@user_subscription_eligiable_check');

Route::post('project_payment_save','Api\V1\SubscriptionApiController@project_payment_save');


// Cards management start

Route::post('cards_add', 'Api\V1\UserAccountApiController@cards_add');

Route::post('cards_list', 'Api\V1\UserAccountApiController@cards_list');

Route::post('cards_delete', 'Api\V1\UserAccountApiController@cards_delete');

Route::post('cards_default', 'Api\V1\UserAccountApiController@cards_default');

Route::post('payment_mode_default', 'Api\V1\UserAccountApiController@payment_mode_default');

// Wallet Tokens 

Route::post('token_payments_save', 'Api\V1\UserCryptoApiController@token_payments_save');

Route::post('token_payments', 'Api\V1\UserCryptoApiController@token_payments');

// Investment

Route::post('invested_projects', 'Api\V1\UserCryptoApiController@invested_projects');

Route::post('projects_investment_save', 'Api\V1\UserCryptoApiController@projects_investment_save');

Route::post('projects_investment_claim', 'Api\V1\UserCryptoApiController@projects_investment_claim');

Route::post('projects_investment_token_validate', 'Api\V1\UserCryptoApiController@projects_investment_token_validate');

// Stacking API's Start 

Route::post('project_stacking_save', 'Api\V1\UserCryptoApiController@project_stacking_save');

Route::post('project_unstacking_save', 'Api\V1\UserCryptoApiController@project_unstacking_save');

Route::post('projects_contract_address_update', 'Api\V1\UserCryptoApiController@projects_contract_address_update');
