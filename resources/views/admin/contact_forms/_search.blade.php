<form method="GET" action="{{route('admin.contact_forms.index')}}">

    <div class="row">


        <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 resp-mrg-btm-md">
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-3 col-md-3 offset-lg-3 offset-md-3 md-full-width resp-mrg-btm-md">

            <select class="form-control select2" name="status">

                <option class="select-color" value="">{{tr('select_status')}}</option>

                <option class="select-color" value="{{CONTACT_FORM_INITIATED}}" @if(Request::get('status') == CONTACT_FORM_INITIATED && Request::get('status')!='' ) selected @endif>{{tr('initiated')}}</option>

                <option class="select-color" value="{{CONTACT_FORM_COMPLETED}}" @if(Request::get('status') == CONTACT_FORM_COMPLETED && Request::get('status')!='' ) selected @endif>{{tr('completed')}}</option>

                <option class="select-color" value="{{CONTACT_FORM_DECLINED}}" @if(Request::get('status') == CONTACT_FORM_DECLINED && Request::get('status')!='' ) selected @endif>{{tr('declined')}}</option>

            </select>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-12">

            <div class="input-group">

                <input type="text" class="form-control" name="search_key" value="{{Request::get('search_key')??''}}" placeholder="{{tr('contact_forms_search_placeholder')}}"> 

                <span class="input-group-btn">
                    &nbsp

                    <button type="submit" class="btn btn-default reset-btn">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>

                    <a href="{{route('admin.contact_forms.index')}}" class="btn btn-default reset-btn">
                        <span> <i class="fa fa-eraser" aria-hidden="true"></i>
                        </span>
                    </a>

                </span>

            </div>

        </div>

    </div>

</form>
<br>