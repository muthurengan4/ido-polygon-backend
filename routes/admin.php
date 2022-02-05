<?php

Route::group(['middleware' => 'web'], function() {

    Route::group(['as' => 'admin.'], function() {

        Route::get('/clear-cache', function() {

            $exitCode = Artisan::call('config:cache');

            return back();

        })->name('clear-cache');


        Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('login');

        Route::post('login', 'Auth\AdminLoginController@login')->name('login.post');

        Route::get('logout', 'Auth\AdminLoginController@logout')->name('logout');

        Route::get('/', 'Admin\AdminController@dashboard')->name('dashboard');

        Route::get('forgot_password','Auth\AdminLoginController@showLinkRequestForm')->name('forgot_password.request');

        Route::post('forgot_password_update', 'Auth\AdminLoginController@forgot_password_update')->name('forgot_password.update');

        // Users CRUD Operations

        Route::get('users', 'Admin\AdminUserController@users_index')->name('users.index');

        Route::get('users/create', 'Admin\AdminUserController@users_create')->name('users.create');

        Route::get('users/edit', 'Admin\AdminUserController@users_edit')->name('users.edit');

        Route::post('users/save', 'Admin\AdminUserController@users_save')->name('users.save');

        Route::get('users/view', 'Admin\AdminUserController@users_view')->name('users.view');

        Route::get('users/delete', 'Admin\AdminUserController@users_delete')->name('users.delete');

        Route::get('users/status', 'Admin\AdminUserController@users_status')->name('users.status');

        Route::get('users/excel','Admin\AdminUserController@users_excel')->name('users.excel');

        Route::get('users/verify', 'Admin\AdminUserController@users_verify_status')->name('users.verify');

        Route::get('users/export/','Admin\AdminUserController@users_export')->name('users.export');

        Route::get('invested_projects', 'Admin\AdminUserController@invested_projects')->name('invested_projects');

        Route::get('invested_projects/view', 'Admin\AdminUserController@invested_projects_view')->name('invested_projects.view');

        Route::get('invested_projects/claim', 'Admin\AdminUserController@invested_projects_claim')->name('invested_projects.claim');
        

        Route::post('/users/bulk_action', 'Admin\AdminUserController@users_bulk_action')->name('users.bulk_action');
        
        Route::post('subscriptions/bulk_action', 'Admin\SubscriptionController@subscriptions_bulk_action')->name('subscriptions.bulk_action');


        Route::get('profile', 'Admin\AdminAccountController@profile')->name('profile');

        Route::post('profile/save', 'Admin\AdminAccountController@profile_save')->name('profile.save');

        Route::post('change/password', 'Admin\AdminAccountController@change_password')->name('change.password');
        
        Route::get('transactions/getaccount', 'Admin\BlockOperationController@transactions_getaccount')->name('transactions.getaccount');

        Route::post('transactions/history', 'Admin\BlockOperationController@transactions_index')->name('transactions.index');

        Route::get('wallets/view', 'Admin\BlockOperationController@transactions_getaccount')->name('wallets.view');

        // settings

        Route::get('control', 'Admin\AdminSettingController@settings_control')->name('settings-control'); 

        Route::get('settings', 'Admin\AdminSettingController@settings')->name('settings'); 

        Route::post('settings/save', 'Admin\AdminSettingController@settings_save')->name('settings.save'); 

        Route::post('env_settings','Admin\AdminSettingController@env_settings_save')->name('env-settings.save');

        // Documents CRUD operations

        Route::get('documents/index', 'Admin\AdminLookupController@documents_index')->name('documents.index');

        Route::get('documents/create', 'Admin\AdminLookupController@documents_create')->name('documents.create');
 
        Route::get('documents/edit', 'Admin\AdminLookupController@documents_edit')->name('documents.edit');
 
        Route::post('documents/save', 'Admin\AdminLookupController@documents_save')->name('documents.save');
 
        Route::get('documents/view', 'Admin\AdminLookupController@documents_view')->name('documents.view');
 
        Route::get('documents/delete', 'Admin\AdminLookupController@documents_delete')->name('documents.delete');
 
        Route::get('documents/status', 'Admin\AdminLookupController@documents_status')->name('documents.status');
 
        //user documents 

        Route::get('user-documents', 'Admin\AdminUserController@user_documents_index')->name('user_documents.index');

        Route::get('user-document', 'Admin\AdminUserController@user_documents_view')->name('user_documents.view');

        Route::get('user-documents/verify', 'Admin\AdminUserController@user_documents_verify')->name('user_documents.verify');

        // STATIC PAGES

        Route::get('static_pages' , 'Admin\AdminLookupController@static_pages_index')->name('static_pages.index');

        Route::get('static_pages/create', 'Admin\AdminLookupController@static_pages_create')->name('static_pages.create');

        Route::get('static_pages/edit', 'Admin\AdminLookupController@static_pages_edit')->name('static_pages.edit');

        Route::post('static_pages/save', 'Admin\AdminLookupController@static_pages_save')->name('static_pages.save');

        Route::get('static_pages/delete', 'Admin\AdminLookupController@static_pages_delete')->name('static_pages.delete');

        Route::get('static_pages/view', 'Admin\AdminLookupController@static_pages_view')->name('static_pages.view');

        Route::get('static_pages/status', 'Admin\AdminLookupController@static_pages_status_change')->name('static_pages.status');

        //faq CRUD
        Route::get('faqs', 'Admin\AdminLookupController@faqs_index')->name('faqs.index');

        Route::get('faqs/create', 'Admin\AdminLookupController@faqs_create')->name('faqs.create');

        Route::get('faqs/edit', 'Admin\AdminLookupController@faqs_edit')->name('faqs.edit');

        Route::post('faqs/save', 'Admin\AdminLookupController@faqs_save')->name('faqs.save');

        Route::get('faqs/view', 'Admin\AdminLookupController@faqs_view')->name('faqs.view');

        Route::get('faqs/delete', 'Admin\AdminLookupController@faqs_delete')->name('faqs.delete');

        Route::get('faqs/status', 'Admin\AdminLookupController@faqs_status')->name('faqs.status');
        //faq end

        Route::get('projects', 'Admin\AdminProjectController@projects_index')->name('projects.index');

        Route::get('projects/create', 'Admin\AdminProjectController@projects_create')->name('projects.create');

        Route::get('projects/edit', 'Admin\AdminProjectController@projects_edit')->name('projects.edit');

        Route::post('projects/save', 'Admin\AdminProjectController@projects_save')->name('projects.save');

        Route::get('projects/view', 'Admin\AdminProjectController@projects_view')->name('projects.view');

        Route::get('projects/delete', 'Admin\AdminProjectController@projects_delete')->name('projects.delete');

        Route::get('projects/status', 'Admin\AdminProjectController@projects_status')->name('projects.status');

        Route::get('projects/publish_status', 'Admin\AdminProjectController@projects_publish_status')->name('projects.publish_status');

        // Subscriptions
        
        Route::get('subscriptions', 'Admin\SubscriptionController@subscriptions_index')->name('subscriptions.index');

        Route::get('subscriptions/create', 'Admin\SubscriptionController@subscriptions_create')->name('subscriptions.create');

        Route::get('subscriptions/edit', 'Admin\SubscriptionController@subscriptions_edit')->name('subscriptions.edit');

        Route::post('subscriptions/save', 'Admin\SubscriptionController@subscriptions_save')->name('subscriptions.save');

        Route::get('subscriptions/view', 'Admin\SubscriptionController@subscriptions_view')->name('subscriptions.view');

        Route::get('subscriptions/delete', 'Admin\SubscriptionController@subscriptions_delete')->name('subscriptions.delete');

        Route::get('subscriptions/status', 'Admin\SubscriptionController@subscriptions_status')->name('subscriptions.status');

        // Subscription Payments

        Route::get('subscription_payments/index','Admin\SubscriptionController@subscription_payments_index')->name('subscription_payments.index');

        Route::get('subscription_payments/view','Admin\SubscriptionController@subscription_payments_view')->name('subscription_payments.view');

        Route::get('revenues/dashboard','Admin\AdminRevenueController@revenues_dashboard')->name('revenues.dashboard');

        // Project Payments

        Route::get('project_payments/index','Admin\AdminProjectController@project_payments_index')->name('project_payments.index');

        Route::get('project_payments/view','Admin\AdminProjectController@project_payments_view')->name('project_payments.view');


        Route::get('token_payments/index','Admin\AdminRevenueController@token_payments_index')->name('token_payments.index');

        Route::get('token_payments/view','Admin\AdminRevenueController@token_payments_view')->name('token_payments.view');


        Route::get('project-view','Admin\AdminProjectController@projects_view_for_web')->name('projects_view_for_web');

        // Ajax Functions


        Route::post('projects/pool_contract_save','Admin\AdminProjectController@projects_pool_contract_save')->name('projects_pool_contract_save');

        Route::post('projects_burn_access_update','Admin\AdminProjectController@projects_burn_access_update')->name('projects_burn_access_update');

        Route::post('projects_mint_access_update','Admin\AdminProjectController@projects_mint_access_update')->name('projects_mint_access_update');

        Route::post('projects_revoke_access','Admin\AdminProjectController@projects_revoke_access')->name('projects_revoke_access');

        Route::post('project_owner_settlement_status','Admin\AdminProjectController@project_owner_settlement_status')->name('project_owner_settlement_status');
        
        Route::post('projects_investors_settlement_status','Admin\AdminProjectController@projects_investors_settlement_status')->name('projects_investors_settlement_status');

        Route::get('contact_forms/index', 'Admin\AdminLookupController@contact_forms_index')->name('contact_forms.index');

        Route::get('contact_forms/view', 'Admin\AdminLookupController@contact_forms_view')->name('contact_forms.view');

        Route::get('contact_forms/status', 'Admin\AdminLookupController@contact_forms_status')->name('contact_forms.status');

    });

});