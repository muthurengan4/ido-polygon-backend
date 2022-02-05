<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Helpers\Helper, App\Helpers\EnvEditorHelper;

use DB, Hash, Setting, Auth, Validator, Exception, Enveditor, Log;

use App\Jobs\SendEmailJob;

use App\Models\Settings;

class AdminSettingController extends Controller
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
     * @method settings_control()
     *
     * @uses  Used to display the setting page
     *
     * @created Akshata
     *
     * @updated
     *
     * @param 
     *
     * @return view page 
     */

    public function settings_control() {

        $env_values = EnvEditorHelper::getEnvValues();

        return view('admin.settings.control')
                ->with('env_values', $env_values)
                ->with('page' , 'settings');
    }

    /**
     * @method settings()
     *
     * @uses  Used to display the setting page
     *
     * @created Akshata
     *
     * @updated
     *
     * @param 
     *
     * @return view page 
     */

    public function settings() {

        $env_values = EnvEditorHelper::getEnvValues();

        return view('admin.settings.settings')
                ->with('env_values', $env_values)
                ->with('page' , 'settings');
    }
    
    /**
     * @method settings_save()
     * 
     * @uses to update settings details
     *
     * @created Akshata
     *
     * @updated 
     *
     * @param (request) setting details
     *
     * @return success/error message
     */
    public function settings_save(Request $request) {
      
        try {
            
            DB::beginTransaction();

            $rules =  
                [
                    'site_logo' => 'mimes:jpeg,jpg,bmp,png',
                    'site_icon' => 'mimes:jpeg,jpg,bmp,png',
                ];

            $custom_errors = ['mimes' => tr('image_error')];

            Helper::custom_validator($request->all(),$rules,$custom_errors);

            foreach($request->toArray() as $key => $value) {

                if($key != '_token') {
                    
                    if(\Enveditor::set($key, $value)) { 
                    
                        Settings::where('key' ,'=', $key)->update(['value' => $value]);

                        DB::commit();

                    } else {
                        
                        if($request->hasFile($key) ) {
                                            
                            $file = Settings::where('key' ,'=', $key)->first();
                           
                            Helper::storage_delete_file($file->value, FILE_PATH_SITE);

                            $file_path = Helper::storage_upload_file($request->file($key) , FILE_PATH_SITE);    

                            $result = Settings::where('key' ,'=', $key)->update(['value' => $file_path]); 

                            if( $result == TRUE ) {
                         
                                DB::commit();
                       
                            } else {

                                throw new Exception(tr('settings_save_error'), 101);
                            } 
                       
                        } else {

                            if(isset($value)) {

                                $result = Settings::where('key' ,'=', $key)->update(['value' => $value]);

                            } else {

                                $result = Settings::where('key' ,'=', $key)->update(['value' => '']);
                            }

                            DB::commit();
                           
                        }

                    }
                      
 
                }
            }

            Helper::settings_generate_json();

            return back()->with('flash_success', tr('settings_update_success'));
            
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('flash_error', $e->getMessage());
        
        }
    }

    /**
     * @method env_settings_save()
     *
     * @uses To update the email details for .env file
     *
     * @created Akshata
     *
     * @updated
     *
     * @param Form data
     *
     * @return view page
     */

    public function env_settings_save(Request $request) {

        try {

            $env_values = EnvEditorHelper::getEnvValues();
           
            if($env_values) {

                foreach ($env_values as $key => $data) {

                    if($request->$key) { 

                        \Enveditor::set($key, $request->$key);

                    }
                }
            }

            $message = tr('settings_update_success');

            return redirect()->route('clear-cache')->with('flash_success', $message);  

        } catch(Exception $e) {

            return back()->withInput()->with('flash_error' , $e->getMessage());

        }  

    }

    /**
     * @method admin_control()
     *
     * @uses 
     *
     * @created Akshata
     *
     * @updated
     *
     * @param 
     *
     * @return view page 
     */
    public function admin_control() {

        $env_values = EnvEditorHelper::getEnvValues();

        return view('admin.settings.control')
                ->with('env_values', $env_values)
                ->with('page' , tr('admin_control'));
        
    }

}
