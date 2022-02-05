<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Setting;

class SampleController extends Controller
{
    public function email_check(Request $request) {

        $user = \App\Models\User::where('users.id', 3)->first();

        $email_data['subject'] = tr('user_welcome_title').' '.Setting::get('site_name');

        $email_data['page'] = "emails.users.welcome";

        $email_data['data'] = $user;

        $email_data['email'] = $user->email;

        $email_data['name'] = $user->name;

        $email_data['verification_code'] = $user->verification_code;

        return view('emails.users.welcome')->with('data', $email_data);
    }
}
