<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log, Validator, Exception, DB, Setting;

use App\Helpers\Helper;

use App\Models\StaticPage;

use App\Models\SubscriptionPayment;

use App\Models\User;

use App\Models\Subscription;

use App\Repositories\PaymentRepository as PaymentRepo;

class ApplicationController extends Controller
{

    protected $loginUser;

    public function __construct(Request $request) {
        
        $this->loginUser = User::find($request->id);

        $this->timezone = $this->loginUser->timezone ?? "America/New_York";

    }

    /**
     * @method static_pages_api()
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

    public function static_pages_api(Request $request) {

        $base_query = \App\StaticPage::where('status', APPROVED)->orderBy('title', 'asc');
                                
        if($request->page_type) {

            $static_pages = $base_query->where('type' , $request->page_type)->first();

        } elseif($request->page_id) {

            $static_pages = $base_query->where('id' , $request->page_id)->first();

        } elseif($request->unique_id) {

            $static_pages = $base_query->where('unique_id' , $request->unique_id)->first();

        } else {

            $static_pages = $base_query->get();

        }

        $response_array = ['success' => true , 'data' => $static_pages ? $static_pages->toArray(): []];

        return response()->json($response_array , 200);

    }

    /**
     * @method static_pages_api()
     *
     * @uses to get the pages
     *
     * @created Bhawya
     *
     * @updated Bhawya
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function static_pages_web(Request $request) {

        $static_page = StaticPage::where('unique_id' , $request->unique_id)
                            ->Approved()
                            ->first();

        $response_array = ['success' => true , 'data' => $static_page];

        return response()->json($response_array , 200);

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
     * @method chat_messages_save()
     * 
     * @uses - To save the chat message.
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

    public function get_notifications_count(Request $request) {

        try {

            Log::info("Notification".print_r($request->all(),true));

            $rules = [
                'user_id' => 'required|exists:users,id',
            ];

            Helper::custom_validator($request->all(),$rules);

            $chat_message = \App\ChatMessage::where('to_user_id', $request->user_id)->where('status',NO);

            $bell_notification = \App\BellNotification::where('to_user_id', $request->user_id)->where('is_read',BELL_NOTIFICATION_STATUS_UNREAD);

            $data['chat_notification'] = $chat_message->count() ?: 0;

            $data['bell_notification'] = $bell_notification->count() ?: 0;

            return $this->sendResponse("", "", $data);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }
    
    }
}
