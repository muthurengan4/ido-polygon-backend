<?php 

namespace App\Helpers;

use Mailgun\Mailgun;

use Hash, Exception, Auth, Mail, File, Log, Storage, Setting, DB, Validator, Image;

use App\Models\Admin, App\Models\User;

use \App\Models\StaticPage, \App\Models\Settings;

class Helper {

    public static function clean($string) {

        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public static function generate_token() {
        
        return Helper::clean(Hash::make(rand() . time() . rand()));
    }

    public static function generate_token_expiry() {

        $token_expiry_hour = Setting::get('token_expiry_hour') ?? 10000;
        
        return time() + $token_expiry_hour*3600;  // 1 Hour
    }

    // Note: $error is passed by reference
    
    public static function is_token_valid($entity, $id, $token, &$error) {

        if (
            ( $entity== USER && ($row = User::where('id', '=', $id)->where('token', '=', $token)->first()) )
        ) {

            if ($row->token_expiry > time()) {
                // Token is valid
                $error = NULL;
                return true;
            } else {
                $error = ['success' => false, 'error' => api_error(1003), 'error_code' => 1003];
                return FALSE;
            }
        }

        $error = ['success' => false, 'error' => api_error(1004), 'error_code' => 1004];
        return FALSE;
   
    }

    public static function generate_email_code($value = "") {

        return mt_rand(100000, 999999);

    }

    public static function generate_email_expiry() {

        $token_expiry = Setting::get('token_expiry_hour') ?: 1;
            
        return time() + $token_expiry*3600;  // 1 Hour

    }
    
    public static function generate_password() {

        $new_password = time();
        $new_password .= rand();
        $new_password = sha1($new_password);
        $new_password = substr($new_password,0,8);
        return $new_password;
    }

    public static function file_name() {

        $file_name = time();
        $file_name .= rand();
        $file_name = sha1($file_name);

        return $file_name;    
    }


    /**
     * @method generate_referral_code()
     *
     * @uses used to genarate referral code to the owner
     *
     * @created Akshata
     * 
     * @updated 
     *
     * @param $value
     *
     * @return boolean
     */
    public static function generate_referral_code($value = "") {

        $referral_name = strtolower(substr(str_replace(' ','',$value),0,3));
        
        $referral_random_number = rand(100,999);

        $referral_code = $referral_name.$referral_random_number;

        return $referral_code;
    }

    /**
     * @method referral_code_earnings_update()
     *
     * @uses used to update referral bonus to the owner
     *
     * @created vithya R
     * 
     * @updated vithya R
     *
     * @param string $referral_code
     *
     * @return boolean
     */

    public static function referral_code_earnings_update($referral_code) {

        $referrer_user = User::where('referral_code', $referral_code)->first();

        if(!$referrer_user) {

            throw new Exception(api_error(132), 132);
            
        }

        $referrer_bonus = Setting::get('referrer_bonus', 1) ?: 0;

        $referrer_user->referrer_bonus += $referrer_bonus;
        
        $referrer_user->save();

        Log::info("referral_code_earnings_update - ".$referrer_bonus);

        return true;

    }

    public static function custom_validator($request, $request_inputs, $custom_errors = []) {

        $validator = Validator::make($request, $request_inputs, $custom_errors);

        if($validator->fails()) {

            $error = implode(',', $validator->messages()->all());

            throw new Exception($error, 101);
               
        }
    }

    /**
      * @method settings_generate_json()
      *
      * @uses used to update settings.json file with updated details.
      *
      * @created vidhya
      * 
      * @updated vidhya
      *
      * @param -
      *
      * @return boolean
      */
    
    public static function settings_generate_json() {

        $settings = Settings::get();

        $sample_data = [];

        foreach ($settings as $key => $setting) {

            $sample_data[$setting->key] = $setting->value;
        }

        $static_page_ids1 = ['about', 'terms', 'privacy', 'contact'];

        $footer_pages1 = StaticPage::whereIn('type', $static_page_ids1)->where('status', APPROVED)->get();

        $static_page_ids2 = ['help', 'faq', 'others'];

        $footer_pages2 = StaticPage::whereIn('type', $static_page_ids2)->where('status', APPROVED)->skip(0)->take(4)->get();

        $sample_data['footer_pages1'] = $footer_pages1;

        $sample_data['footer_pages2'] = $footer_pages2;

        // Social logins

        $social_login_keys = ['FB_CLIENT_ID', 'FB_CLIENT_SECRET', 'FB_CALL_BACK' , 'TWITTER_CLIENT_ID', 'TWITTER_CLIENT_SECRET', 'TWITTER_CALL_BACK', 'GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET', 'GOOGLE_CALL_BACK'];

        $social_logins = Settings::whereIn('key', $social_login_keys)->get();

        $social_login_data = [];

        foreach ($social_logins as $key => $social_login) {

            $social_login_data[$social_login->key] = $social_login->value;
        }

        $sample_data['social_logins'] = $social_login_data;

        $sample_data['lp_convertion_formatted'] = Setting::get('network_token_amount') ." ". Setting::get('network_token').' = '.Setting::get('lp_convertion_amount')." ".Setting::get('currency');

        $data['data'] = $sample_data;

        $data = json_encode($data);

        $folder_path_name = 'default-json/settings.json';

        Storage::disk('public')->put($folder_path_name, $data);
    
    }

    /**
      * @method upload_file
      */
    
    public static function storage_upload_file($input_file, $folder_path = COMMON_FILE_PATH, $name = "") {

        if(!$input_file) {

            return "";

        }

        if(Setting::get('s3_bucket') == STORAGE_TYPE_S3 ) {

            $path = $input_file->store($folder_path, 's3');

            $file_path = str_replace("//","/",$path);

            $url = Storage::disk('s3')->url($file_path);

            return $url;
        }
       
        $name = $name ?: Helper::file_name();

        $ext = $input_file->getClientOriginalExtension();

        $file_name = $name.".".$ext;

        $public_folder_path = "public/".$folder_path;

        Storage::putFileAs($public_folder_path, $input_file, $file_name);

        $storage_file_path = $folder_path.$file_name;

        $url = asset(Storage::url($storage_file_path));
    
        return $url;

    }

    /**
     * @method
     * 
     */
    public static function storage_delete_file($url, $folder_path = COMMON_FILE_PATH) {

        $file_name = basename($url);

        $storage_file_path = $folder_path.$file_name;

        if (Setting::get('s3_bucket') == STORAGE_TYPE_S3 ) {

            $s3 = Storage::disk('s3');

            $s3->delete($storage_file_path);

            return true;
        }

        $response = Storage::disk('public')->delete($storage_file_path);
    
    }


}
