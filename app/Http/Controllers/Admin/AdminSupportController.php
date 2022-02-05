<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB, Log, Setting;

use \App\Helpers\Helper;

use \App\Models\SupportCategory, \App\Models\SupportTicket, \App\Models\SupportChat;

class AdminSupportController extends Controller
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
     * @method support_categories_index()
     *
     * @uses To list out categories details 
     *
     * @created Vithya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function support_categories_index(Request $request) {

        $support_categories = SupportCategory::orderBy('created_at','DESC')->paginate($this->paginate_count);

        return view('admin.support_categories.index')
                ->with('page', 'support_categories')
                ->with('sub_page' , 'support_categories-view')
                ->with('support_categories' , $support_categories);
    
    }

    /**
     * @method support_categories_create()
     *
     * @uses To create category details
     *
     * @created  Vithya
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function support_categories_create() {

        $support_category = new SupportCategory;

        return view('admin.support_categories.create')
                ->with('page', 'support_categories')
                ->with('sub_page', 'support_categories-create')
                ->with('support_category', $support_category);           
    }

    /**
     * @method support_categories_edit()
     *
     * @uses To display and update category details based on the category id
     *
     * @created Vithya
     *
     * @updated 
     *
     * @param object $request - Category Id 
     * 
     * @return redirect view page 
     *
     */
    public function support_categories_edit(Request $request) {

        try {

            $support_category = SupportCategory::find($request->support_category_id);

            if(!$support_category) { 

                throw new Exception(tr('support_category_not_found'), 101);
            }

            return view('admin.support_categories.edit')
                ->with('page' , 'support_categories')
                ->with('sub_page', 'support_categories-view')
                ->with('support_category', $support_category); 
            
        } catch(Exception $e) {

            return redirect()->route('admin.support_categories.index')->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method support_categories_save()
     *
     * @uses To save the category details of new/existing category object based on details
     *
     * @created Vithya
     *
     * @updated 
     *
     * @param object request - Category Form Data
     *
     * @return success message
     *
     */
    public function support_categories_save(Request $request) {
        
        try {
            
            DB::begintransaction();

            $rules = [
                'name' => 'required|max:191',
                'picture' => 'mimes:jpg,png,jpeg',
                'discription' => 'max:199',
            ];

            Helper::custom_validator($request->all(),$rules);

            $support_category = $request->support_category_id ? SupportCategory::find($request->support_category_id) : new SupportCategory;

            $message = $support_category->id ? tr('support_category_updated_success') :  tr('support_category_created_success') ;

            $support_category->name = $request->name ?: $support_category->name;

            $support_category->description = $request->description ?: '';

            // Upload picture
            
            if($request->hasFile('picture')) {

                if($request->support_category_id) {

                    Helper::storage_delete_file($support_category->picture, CATEGORY_FILE_PATH); 
                    // Delete the old pic
                }

                $support_category->picture = Helper::storage_upload_file($request->file('picture'), CATEGORY_FILE_PATH);
            }

            if($support_category->save()) {

                DB::commit(); 

                return redirect(route('admin.support_categories.view', ['support_category_id' => $support_category->id]))->with('flash_success', $message);

            } 

            throw new Exception(tr('support_category_save_failed'));
            
        } catch(Exception $e){ 

            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());

        } 

    }

    /**
     * @method support_categories_view()
     *
     * @uses displays the specified category details based on category id
     *
     * @created Vithya 
     *
     * @updated 
     *
     * @param object $request - category Id
     * 
     * @return View page
     *
     */
    public function support_categories_view(Request $request) {
       
        try {
      
            $support_category = SupportCategory::find($request->support_category_id);

            if(!$support_category) { 

                throw new Exception(tr('support_category_not_found'), 101);                
            }

            return view('admin.support_categories.view')
                    ->with('page', 'support_categories') 
                    ->with('sub_page', 'support_categories-view')
                    ->with('support_category', $support_category);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method support_categories_delete()
     *
     * @uses delete the category details based on category id
     *
     * @created Vithya 
     *
     * @updated  
     *
     * @param object $request - Category Id
     * 
     * @return response of success/failure details with view page
     *
     */
    public function support_categories_delete(Request $request) {

        try {

            DB::begintransaction();

            $support_category = SupportCategory::find($request->support_category_id);
            
            if(!$support_category) {

                throw new Exception(tr('support_category_not_found'), 101);                
            }

            if($support_category->delete()) {

                DB::commit();

                return redirect()->route('admin.support_categories.index')->with('flash_success',tr('support_category_deleted_success'));   

            } 
            
            throw new Exception(tr('support_category_delete_failed'));
            
        } catch(Exception $e){

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }       
         
    }

    /**
     * @method support_categories_status
     *
     * @uses To update category status as DECLINED/APPROVED based on category id
     *
     * @created Vithya
     *
     * @updated 
     *
     * @param object $request - Category Id
     * 
     * @return response success/failure message
     *
     **/
    public function support_categories_status(Request $request) {

        try {

            DB::beginTransaction();

            $support_category = SupportCategory::find($request->support_category_id);

            if(!$support_category) {

                throw new Exception(tr('support_category_not_found'), 101);
                
            }

            $support_category->status = $support_category->status ? DECLINED : APPROVED ;

            if($support_category->save()) {

                DB::commit();

                return redirect()->back()->with('flash_success', $message);
            }
            
            throw new Exception(tr('support_category_status_change_failed'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.support_categories.index')->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method support_tickets_index()
     *
     * @uses Display the lists of support tickets
     *
     * @created vithya R
     *
     * @updated
     *
     * @param -
     *
     * @return view page 
     */
    public function support_tickets_index(Request $request) {

        $support_tickets = SupportTicket::orderBy('created_at','DESC')->paginate($this->paginate_count);


        return view('admin.support_tickets.index')
                    ->with('page', 'support_tickets')
                    ->with('sub_page', 'support_tickets-view')
                    ->with('support_tickets', $support_tickets);
    }

    /**
     * @method support_tickets_view()
     *
     * @uses displays the specified support tickets details based on support ticket id
     *
     * @created vithya R 
     *
     * @updated 
     *
     * @param object $request -  Support Ticket Id
     * 
     * @return View page
     *
     */
    public function support_tickets_view(Request $request) {
       
        try {
      
            $support_ticket = SupportTicket::find($request->support_ticket_id);

            if(!$support_ticket) { 

                throw new Exception(tr('support_ticket_not_found'), 101);                
            }
        
            return view('admin.support_tickets.view')
                        ->with('page', 'support_tickets') 
                        ->with('sub_page','support_tickets-view') 
                        ->with('support_ticket' , $support_ticket);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method support_tickets_create()
     *
     * @uses To create subscriptions details
     *
     * @created  
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function support_tickets_create() {

        $support_ticket = new SupportTicket;

        $users = \App\Models\User::orderby('name', 'desc')->Approved()->get();

        foreach ($users as $key => $user_details) {

            $user_details->is_selected = NO;
        }

        $support_members = \App\SupportMember::Approved()->orderBy('name', 'desc')->get();

        foreach ($support_members as $key => $support_member) {
            $support_member->is_selected = NO;
        }

        return view('admin.support_tickets.create')
                    ->with('page', 'support_ticket')
                    ->with('sub_page','support_ticket-create')
                    ->with('support_ticket', $support_ticket)
                    ->with('users', $users)
                    ->with('support_members', $support_members);                    

    }

    /**
     * @method support_tickets_save()
     *
     * @uses To save the support_tickets details of new/existing subscription object based on details
     *
     * @created 
     *
     * @updated 
     *
     * @param object request - Subscrition Form Data
     *
     * @return success message
     *
     */
    public function support_tickets_save(Request $request) {

        try {

            DB::begintransaction();

            $rules = [
                'user_id' => 'required',
                'subject'  => 'required|max:255',
                'message' => 'max:255',
                

            ];

            Helper::custom_validator($request->all(),$rules);


            $support_ticket = SupportTicket::find($request->support_ticket_id) ?? new SupportTicket;

            $support_ticket->status = APPROVED;
            
            $support_ticket->user_id = $request->user_id;

            $support_ticket->support_member_id = $request->support_member_id;

            $support_ticket->subject = $request->subject ?: "";

            $support_ticket->message = $request->message ?: "";

            

            if( $support_ticket->save() ) {

                DB::commit();

                $message = $request->support_ticket_id ? tr('support_ticket_update_success')  : tr('support_ticket_create_success');

                return redirect()->route('admin.support_tickets.view', ['support_ticket_id' => $support_ticket->id])->with('flash_success', $message);
            } 

            throw new Exception(tr('support_ticket_saved_error') , 101);

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());
        } 

    }

    /**
     * @method support_tickets_edit()
     *
     * @support_ticket To display and update support_tickets details based on the support_ticket id
     *
     * @created 
     *
     * @updated 
     *
     * @param object $request - Support_ticket Id
     * 
     * @return redirect view page 
     *
     */
    public function support_tickets_edit(Request $request) {

        try {

            $support_ticket = SupportTicket::find($request->support_ticket_id);

            if(!$support_ticket) {

                throw new Exception(tr('support_ticket_not_found'), 101);
            }

            $users = \App\Models\User::orderby('name', 'desc')->Approved()->get();

            foreach ($users as $key => $user_details) {

                $user_details->is_selected = $user_details->id == $support_ticket->user_id ? YES : NO;
            }

            $support_members = \App\SupportMember::Approved()->orderBy('name', 'desc')->get();

            foreach ($support_members as $key => $support_member) {
                $support_member->is_selected = $support_member->id == $support_ticket->support_member_id ? YES : NO;

            }

            return view('admin.support_tickets.edit')
                    ->with('page', 'support_tickets')
                    ->with('sub_page', 'support_ticket-view')
                    ->with('support_ticket', $support_ticket)
                    ->with('users', $users)
                    ->with('support_members', $support_members);
            
        } catch(Exception $e) {

            return redirect()->route('admin.support_tickets.index')->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method support_tickets_delete()
     *
     * @uses delete the support_tickets details based on support_ticket id
     *
     * @created  
     *
     * @updated  
     *
     * @param object $request - Support_ticket Id
     * 
     * @return response of success/failure details with view page
     *
     */
    public function support_tickets_delete(Request $request) {

        try {

            DB::begintransaction();

            $support_ticket = SupportTicket::find($request->support_ticket_id);
            
            if(!$support_ticket) {

                throw new Exception(tr('support_tickets_not_found'), 101);                
            }

            if($support_ticket->delete()) {

                DB::commit();

                return redirect()->route('admin.support_tickets.index')->with('flash_success',tr('support_ticket_deleted_success'));   

            } 
            
            throw new Exception(tr('support_ticket_delete_failed'));
            
        } catch(Exception $e){

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }       
         
    }

    /**
     * @method support_chats_index()
     *
     * @uses Display the lists of support tickets
     *
     * @created vithya R
     *
     * @updated
     *
     * @param -
     *
     * @return view page 
     */
    public function support_chats_index(Request $request) {

        $support_chats = SupportChat::where('support_ticket_id', $request->support_ticket_id)->get();

        return view('admin.support_tickets.index')
                    ->with('page', 'support_chats')
                    ->with('sub_page', 'support_tickets-view')
                    ->with('support_chats', $support_chats);
    }
}
