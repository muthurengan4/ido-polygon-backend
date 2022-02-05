<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB, Log, Setting;

use \App\Helpers\Helper;

use \App\Models\StaticPage;

use \App\Models\Faq;

use \App\Models\Document, App\Models\ContactForm;

class AdminLookupController extends Controller
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
     * @method static_pages_index()
     *
     * @uses Used to list the static pages
     *
     * @created vithya
     *
     * @updated vithya  
     *
     * @param -
     *
     * @return List of pages   
     */

    public function static_pages_index() {

        $static_pages = StaticPage::orderBy('updated_at' , 'desc')->paginate($this->paginate_count);

        return view('admin.static_pages.index')
                    ->with('page', 'static_pages')
                    ->with('sub_page', 'static_pages-view')
                    ->with('static_pages', $static_pages);
    
    }

    /**
     * @method static_pages_create()
     *
     * @uses display create static page 
     *
     * @created Vithya R
     *
     * @updated    
     *
     * @param
     *
     * @return view page   
     *
     */
    public function static_pages_create() {

        $static_keys = ['about' , 'contact' , 'privacy' , 'terms' , 'help' , 'faq' , 'refund', 'cancellation'];

        foreach ($static_keys as $key => $static_key) {

            // Check the record exists

            $check_page = StaticPage::where('type', $static_key)->first();

            if($check_page) {

                unset($static_keys[$key]);

            }
        }

        // $section_types = static_page_footers(0, $is_list = YES);
        $section_types = [];

        $static_keys[] = 'others';

        $static_page = new StaticPage;

        return view('admin.static_pages.create')
                ->with('page', 'static_pages')
                ->with('sub_page', 'static_pages-create')
                ->with('static_keys', $static_keys)
                ->with('static_page', $static_page)
                ->with('section_types',$section_types);
   
    }

    /**
     * @method static_pages_edit()
     *
     * @uses To display and update static_page details based on the static_page id
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param object $request - static_page Id
     * 
     * @return redirect view page 
     *
     */
    public function static_pages_edit(Request $request) {

        try {

            $static_page = StaticPage::find($request->static_page_id);

            if(!$static_page) {

                throw new Exception(tr('static_page_not_found'), 101);
            }

            $static_keys = ['about' , 'contact' , 'privacy' , 'terms' , 'help' , 'faq' , 'refund', 'cancellation'];

            foreach ($static_keys as $key => $static_key) {

                // Check the record exists

                $check_page = StaticPage::where('type', $static_key)->first();

                if($check_page) {
                    unset($static_keys[$key]);
                }
            }

            $static_keys[] = 'others';

            $static_keys[] = $static_page->type;


            $section_types = static_page_footers(0, $is_list = YES);
 
            return view('admin.static_pages.edit')
                    ->with('page', 'static_pages')
                    ->with('sub_page', 'static_pages-view')
                    ->with('static_keys', array_unique($static_keys))
                    ->with('static_page', $static_page)
                    ->with('section_types',$section_types);

        } catch(Exception $e) {

            return redirect()->route('admin.static_pages.index')->with('flash_error', $e->getMessage());

        }
    }

    /**
     * @method static_pages_save()
     *
     * @uses To save the page details of new/existing page object based on details
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param
     *
     * @return index page    
     *
     */
    public function static_pages_save(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [
                'title' =>  !$request->static_page_id ? 'required|max:191|unique:static_pages,title' : 'required',
                'description' => 'required',
                'type' => !$request->static_page_id ? 'required' : ""
            ]; 
            
            Helper::custom_validator($request->all(), $rules);

            if($request->static_page_id != '') {

                $static_page = StaticPage::find($request->static_page_id);

                $message = tr('static_page_updated_success');                    

            } else {

                $check_page = "";

                // Check the staic page already exists

                if($request->type != 'others') {

                    $check_page = StaticPage::where('type',$request->type)->first();

                    if($check_page) {

                        return back()->with('flash_error',tr('static_page_already_alert'));
                    }

                }

                $message = tr('static_page_created_success');

                $static_page = new StaticPage;

                $static_page->status = APPROVED;

            }

            $static_page->title = $request->title ?: $static_page->title;

            $static_page->description = $request->description ?: $static_page->description;

            $static_page->type = $request->type ?: $static_page->type;
            
            $static_page->section_type = $request->section_type ?: $static_page->section_type;

            $unique_id = $request->type ?: $static_page->type;

            // Dont change the below code. If any issue, get approval from vithya and change

            if(!in_array($unique_id, ['about', 'privacy', 'terms', 'contact', 'help', 'faq'])) {

                $unique_id = routefreestring($request->heading ?? rand());

                $unique_id = in_array($unique_id, ['about', 'privacy', 'terms', 'contact', 'help', 'faq']) ? $unique_id : $unique_id;

            }

            $static_page->unique_id = $unique_id ?? rand();

            if($static_page->save()) {

                DB::commit();

                Helper::settings_generate_json();
                
                return redirect()->route('admin.static_pages.view', ['static_page_id' => $static_page->id] )->with('flash_success', $message);

            } 

            throw new Exception(tr('static_page_save_failed'), 101);
                      
        } catch(Exception $e) {

            DB::rollback();

            return back()->withInput()->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method static_pages_delete()
     *
     * Used to view file of the create the static page 
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param -
     *
     * @return view page   
     */

    public function static_pages_delete(Request $request) {

        try {

            DB::beginTransaction();

            $static_page = StaticPage::find($request->static_page_id);

            if(!$static_page) {

                throw new Exception(tr('static_page_not_found'), 101);
                
            }

            if($static_page->delete()) {

                DB::commit();

                return redirect()->route('admin.static_pages.index',['page'=>$request->page])->with('flash_success', tr('static_page_deleted_success')); 

            } 

            throw new Exception(tr('static_page_error'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.static_pages.index')->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method static_pages_view()
     *
     * @uses view the static_pages details based on static_pages id
     *
     * @created Vithya R 
     *
     * @updated 
     *
     * @param object $request
     * 
     * @return View page
     *
     */
    public function static_pages_view(Request $request) {

        $static_page = StaticPage::find($request->static_page_id);

        if(!$static_page) {
           
            return redirect()->route('admin.static_pages.index')->with('flash_error',tr('static_page_not_found'));

        }

        return view('admin.static_pages.view')
                    ->with('page', 'static_pages')
                    ->with('sub_page', 'static_pages-view')
                    ->with('static_page', $static_page);
    }

    /**
     * @method static_pages_status_change()
     *
     * @uses To update static_page status as DECLINED/APPROVED based on static_page id
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param - integer static_page_id
     *
     * @return view page 
     */

    public function static_pages_status_change(Request $request) {

        try {

            DB::beginTransaction();

            $static_page = StaticPage::find($request->static_page_id);

            if(!$static_page) {

                throw new Exception(tr('static_page_not_found'), 101);
                
            }

            $static_page->status = $static_page->status == DECLINED ? APPROVED : DECLINED;

            $static_page->save();

            DB::commit();

            $message = $static_page->status == DECLINED ? tr('static_page_decline_success') : tr('static_page_approve_success');

            return redirect()->back()->with('flash_success', $message);

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method faqs_index()
     *
     * @uses To list out faq details 
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function faqs_index() {
       
        $faqs = Faq::orderBy('created_at','desc')->paginate($this->paginate_count);

        return view('admin.faqs.index')
                    ->with('main_page','faqs-crud')
                    ->with('page','faqs')
                    ->with('sub_page' , 'faqs-view')
                    ->with('faqs' , $faqs);
    
    }

    /**
     * @method faqs_create()
     *
     * @uses To create faq details
     *
     * @created  Vithya R
     *
     * @updated 
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function faqs_create() {

        $faq = new Faq;

        return view('admin.faqs.create')
                    ->with('main_page','faqs-crud')
                    ->with('page' , 'faqs')
                    ->with('sub_page','faqs-create')
                    ->with('faq', $faq);
                
    }

    /**
     * @method faqs_edit()
     *
     * @uses To display and update faqs details based on the faq id
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param object $request - Faq Id
     * 
     * @return redirect view page 
     *
     */
    public function faqs_edit(Request $request) {

        try {

            $faq = Faq::find($request->faq_id);

            if(!$faq) { 

                throw new Exception(tr('faq_not_found'), 101);

            }
           
            return view('admin.faqs.edit')
                    ->with('main_page','faqs-crud')
                    ->with('page' , 'faqs')
                    ->with('sub_page','faqs-view')
                    ->with('faq' , $faq); 
            
        } catch(Exception $e) {

            return redirect()->route('admin.faqs.index')->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method faqs_save()
     *
     * @uses To save the faqs details of new/existing Faq object based on details
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param object request - Faq Form Data
     *
     * @return success message
     *
     */
    public function faqs_save(Request $request) {

        try {

            DB::begintransaction();

            $rules = [
                'question' => 'required',
                'answer' => 'required',
            
            ];

            Helper::custom_validator($request->all(),$rules);

            $faq = $request->faq_id ? Faq::find($request->faq_id) : new Faq;

            if(!$faq) {

                throw new Exception(tr('faq_not_found'), 101);
            }

            $faq->question = $request->question;

            $faq->answer = $request->answer;

            $faq->status = APPROVED;

            if($faq->save() ) {

                DB::commit();

                $message = $request->faq_id ? tr('faq_update_success')  : tr('faq_create_success');

                return redirect()->route('admin.faqs.view', ['faq_id' => $faq->id])->with('flash_success', $message);
            } 

            throw new Exception(tr('faq_saved_error') , 101);

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());
        } 

    }

    /**
     * @method faqs_view()
     *
     * @uses view the faqs details based on faq id
     *
     * @created Vithya R 
     *
     * @updated 
     *
     * @param object $request - Faq Id
     * 
     * @return View page
     *
     */
    public function faqs_view(Request $request) {
       
        try {
      
            $faq = Faq::find($request->faq_id);
            
            if(!$faq) { 

                throw new Exception(tr('faq_not_found'), 101);                
            }

            return view('admin.faqs.view')
                        ->with('main_page','faqs-crud')
                        ->with('page', 'faqs') 
                        ->with('sub_page','faqs-view') 
                        ->with('faq' , $faq);
            
        } catch (Exception $e) {

            return redirect()->back()->with('flash_error', $e->getMessage());
        }
    
    }

    /**
     * @method faqs_delete()
     *
     * @uses delete the faq details based on faq id
     *
     * @created Vithya R 
     *
     * @updated  
     *
     * @param object $request - Faq Id
     * 
     * @return response of success/failure details with view page
     *
     */
    public function faqs_delete(Request $request) {

        try {

            DB::begintransaction();

            $faq = Faq::find($request->faq_id);
            
            if(!$faq) {

                throw new Exception(tr('faq_not_found'), 101);                
            }

            if($faq->delete()) {

                DB::commit();

                return redirect()->route('admin.faqs.index',['page'=>$request->page])->with('flash_success',tr('faq_deleted_success'));   

            } 
            
            throw new Exception(tr('faq_delete_failed'));
            
        } catch(Exception $e){

            DB::rollback();

            return redirect()->back()->with('flash_error', $e->getMessage());

        }       
         
    }

    /**
     * @method faqs_status
     *
     * @uses To update faq status as DECLINED/APPROVED based on faqs id
     *
     * @created Vithya R
     *
     * @updated 
     *
     * @param object $request - Faq Id
     * 
     * @return response success/failure message
     *
     **/
    public function faqs_status(Request $request) {

        try {

            DB::beginTransaction();

            $faq = Faq::find($request->faq_id);

            if(!$faq) {

                throw new Exception(tr('faq_not_found'), 101);
                
            }

            $faq->status = $faq->status ? DECLINED : APPROVED ;

            if($faq->save()) {

                DB::commit();

                $message = $faq->status ? tr('faq_approve_success') : tr('faq_decline_success');

                return redirect()->back()->with('flash_success', $message);
            }
            
            throw new Exception(tr('faq_status_change_failed'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.faqs.index')->with('flash_error', $e->getMessage());

        }

    }

    /**
     * @method documents_index()
     *
     * @uses To display document list page
     *
     * @created Arun
     *
     * @updated 
     *
     * @param 
     *
     * @return view page
     */

    public function documents_index(Request $request) {

        $base_query = Document::orderBy('updated_at','desc');

        if($request->search_key){

            $base_query = $base_query->where('name','LIKE','%'.$request->search_key.'%');
        }

        $documents = $base_query->paginate($this->paginate_count);

        return view('admin.documents.index')
                    ->with('page' , 'documents')
                    ->with('sub_page','documents-index')
                    ->with('documents' , $documents);
    
    }

    /**
     * @method documents_create()
     *
     * @uses To create document details
     *
     * @created Arun
     *
     * @updated 
     *
     * @param -
     *
     * @return view page
     */
    public function documents_create() {

        $document = new Document;
        
        return view('admin.documents.create')
                ->with('page' , 'documents')
                ->with('sub_page','documents-create')
                ->with('document', $document);
    
    }

    /**
     * @method documents_save()
     *
     * @uses To save the details based on document or to create a new document
     *
     * @created Arun
     *
     * @updated 
     *
     * @param object $request - document object details
     * 
     * @return success/failure message
     *
     */
    public function documents_save(Request $request) {

        try {

            DB::beginTransaction();

            $rules = [

                'name' => 'required|max:191',
                'description' => 'max:191',
                'picture' => 'image|mimes:jpeg,png,jpg,gif,svg'
            ];
            
            Helper::custom_validator($request->all(),$rules);
            
            $document = Document::find($request->document_id) ?? new Document;

            $message = $request->document_id ? tr('document_updated_success') :tr('document_created_success');

            if($request->hasFile('picture')) {

                Helper::storage_delete_file($request->file('picture'), COMMON_FILE_PATH);

                $document->picture = Helper::storage_upload_file($request->file('picture'), COMMON_FILE_PATH);

            }

            $document->name = $request->name ?: $document->name;
            
            $document->description = $request->description ?: '';

            if($document->save()) {

                DB::commit();

                return redirect()->route('admin.documents.view', ['document_id' => $document->id])->with('flash_success', $message);

            }

            return back()->with('flash_error', tr('document_save_failed'));
            
        } catch(Exception $e) {

            DB::rollback();

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());

        }
        
    }

    /**
     * @method documents_view()
     *
     * @uses view the document details based on document id
     *
     * @created Ganesh 
     *
     * @updated 
     *
     * @param object $request - document Id
     * 
     * @return View page
     *
     */
    public function documents_view(Request $request) {

     try {

        $document = Document::find($request->document_id);

        if(!$document) {

            return redirect()->route('admin.documents.index')->with('flash_error',tr('document_not_found'));

        }

        return view('admin.documents.view')
                    ->with('page', 'documents')
                    ->with('sub_page','documents-index')
                    ->with('document' , $document);

        } catch(Exception $e) {

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());

        }
    
    } 

    /**
     * @method documents_edit()
     *
     * @uses To display and update document details based on the document id
     *
     * @created Ganesh
     *
     * @updated 
     *
     * @param object $request - document Id
     * 
     * @return redirect view page 
     *
     */
    public function documents_edit(Request $request) {

      try {

        $document = Document::find($request->document_id);
       
        if(!$document) {

            return back()->with('flash_error', tr('document_not_found'));
        }

        return view('admin.documents.edit')
                    ->with('page','documents')
                    ->with('sub_page','documents-create')
                    ->with('document',$document);
        
        } catch(Exception $e) {

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());

        }
    
    }

    /**
     * @method documents_status()
     *
     * @uses To delete the document details based on document id
     *
     * @created Ganesh
     *
     * @updated 
     *
     * @param integer $document_id
     * 
     * @return response success/failure message
     *
     */
    public function documents_status(Request $request) {

        try {

            DB::beginTransaction();

            $document = Document::find($request->document_id);

            if(!$document) {

                throw new Exception(tr('document_not_found'), 101);
                
            }

            $document->status = $document->status ? DECLINED : APPROVED;

            if( $document->save()) {

                DB::commit();

                $message = $document->status ? tr('document_approve_success') : tr('document_decline_success');

                return redirect()->back()->with('flash_success', $message);

            } 

            throw new Exception(tr('document_status_change_failed'));
                
        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.documents.index')->with('flash_error', $e->getMessage());
        }

    }

    /**
     * @method documents_delete
     *
     * @uses To delete the document details based on selected document id
     *
     * @created Ganesh
     *
     * @updated 
     *
     * @param integer $document_id
     * 
     * @return response of success/failure details
     *
     */
    public function documents_delete(Request $request) {

        try {

            DB::beginTransaction();

            $document = Document::find($request->document_id);

            if(!$document) {

                throw new Exception(tr('document_not_found'), 101);
                
            }

            if($document->delete()) {

                DB::commit();

                return redirect()->route('admin.documents.index')->with('flash_success',tr('document_deleted_success')); 

            } 

            throw new Exception(tr('document_delete_failed'));

        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.documents.index')->with('flash_error', $e->getMessage());

        }
   
    }

    /**
     * @method contact_forms_index()
     *
     * @uses To list out contact form details 
     *
     * @created Arun
     *
     * @updated Subham Kant
     *
     * @param 
     * 
     * @return return view page
     *
     */
    public function contact_forms_index(Request $request) {
       
        $base_query = ContactForm::orderBy('created_at','desc');

        if($request->search_key) {

            $search_key = $request->search_key;

            $search_contact_form_ids = ContactForm::where('contact_forms.title', 'LIKE','%'.$search_key.'%')
                            ->orWhere('contact_forms.email', 'LIKE','%'.$search_key.'%')
                            ->orWhere('contact_forms.name', 'LIKE','%'.$search_key.'%')
                            ->pluck('id');

            $base_query = $base_query->whereIn('contact_forms.id',$search_contact_form_ids);

        }

        if($request->status) {

            $base_query = $base_query->where('contact_forms.status', $request->status);
        }

        $contact_forms = $base_query->paginate($this->paginate_count);

        return view('admin.contact_forms.index')
                    ->with('main_page','contact-forms')
                    ->with('page','contact-forms')
                    ->with('contact_forms' , $contact_forms);
    
    }

    /**
     * @method contact_forms_view()
     *
     * @uses view the contact_forms details based on contact_form id
     *
     * @created Aru  
     *
     * @updated 
     *
     * @param object $request - contact_form Id
     * 
     * @return View page
     *
     */
    public function contact_forms_view(Request $request) {

     try {

        $contact_form = ContactForm::find($request->contact_form_id);

        if(!$contact_form) {

            return redirect()->route('admin.contact_forms.index')->with('flash_error',tr('contact_form_not_found'));

        }

        return view('admin.contact_forms.view')
                    ->with('page', 'contact-forms')
                    ->with('contact_form' , $contact_form);

        } catch(Exception $e) {

            return redirect()->back()->withInput()->with('flash_error', $e->getMessage());

        }
    
    } 

    /**
     * @method contact_forms_status()
     *
     * @uses 
     *
     * @created Arun
     *
     * @updated 
     *
     * @param integer $contact_form_id
     * 
     * @return response success/failure message
     *
     */
    public function contact_forms_status(Request $request) {

        try {

            DB::beginTransaction();

            $contact_form = ContactForm::find($request->contact_form_id);

            if(!$contact_form) {

                return redirect()->route('admin.contact_forms.index')->with('flash_error',tr('contact_form_not_found'));

            }

            $contact_form->status = $request->status ?? CONTACT_FORM_INITIATED;

            if( $contact_form->save()) {

                DB::commit();

                return redirect()->back()->with('flash_success', tr('contact_form_status_change_success'));

            } 

            throw new Exception(tr('contact_form_status_change_failed'));
                
        } catch(Exception $e) {

            DB::rollback();

            return redirect()->route('admin.contact_forms.index')->with('flash_error', $e->getMessage());
        }

    }
}
