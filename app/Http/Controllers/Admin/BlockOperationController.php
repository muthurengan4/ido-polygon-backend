<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlockOperationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {

        $this->middleware('auth:admin');
       
        $this->paginate_count = 12;
        
    }

    /**
     * @method transactions_getaccount()
     *
     * @uses To list out users details 
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function transactions_getaccount(Request $request) {

        return view('admin.transactions.getaccount');
    
    }

    /**
     * @method transactions_index()
     *
     * @uses To list out users details 
     *
     * @created Vidhya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function transactions_index(Request $request) {

        try {

            $apiKey = "MA398CNCQN6P4SAXIDRKKSGDJQ7RUUHK7Y";

            $account = $request->account ?: "0x33146037f201d91d2832ecf06e42c5c322b4edae";

            $httpClient = new \GuzzleHttp\Client();

            $normal_transactions = "https://api.etherscan.io/api?module=account&action=txlist&address=$account&startblock=0&endblock=99999999&sort=asc&apikey=$apiKey";

            $request = $httpClient->get($normal_transactions);

            $response = json_decode($request->getBody()->getContents());

            // dd($response->result);

            return view('admin.transactions.index')->with('transactions', $response->result);

        } catch(Exception $e) {

            dd($e->getMessage());

        }

    
    }

}
