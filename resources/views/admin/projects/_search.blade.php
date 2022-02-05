<form method="GET" action="{{route('admin.projects.index')}}">

    <div class="row">

        <input type="hidden" id="user_id" name="user_id" value="{{Request::get('user_id') ?? ''}}">

        <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12 resp-mrg-btm-md">
            @if(Request::has('search_key'))
            <p class="text-muted">Search results for <b>{{Request::get('search_key')}}</b></p>
            @endif
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-3 col-md-6 md-full-width resp-mrg-btm-md">

            <select class="form-control select2" name="status">

                <option class="select-color" value="">{{tr('select_status')}}</option>

                <option class="select-color" value="{{SORT_BY_APPROVED}}" @if(Request::get('status') == SORT_BY_APPROVED && Request::get('status')!='' ) selected @endif>{{tr('approved')}}</option>

                <option class="select-color" value="{{SORT_BY_DECLINED}}" @if(Request::get('status') == SORT_BY_DECLINED && Request::get('status')!='' ) selected @endif>{{tr('declined')}}</option>

            </select>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-3 col-md-6 md-full-width resp-mrg-btm-md">

            <select class="form-control select2" name="publish_status">

                <option class="select-color" value="">{{tr('publish_status')}}</option>

                <option class="select-color" value="{{PROJECT_PUBLISH_STATUS_INITIATED}}" @if(Request::get('publish_status') == PROJECT_PUBLISH_STATUS_INITIATED && Request::get('publish_status')!='' ) selected @endif>{{tr('PROJECT_PUBLISH_STATUS_INITIATED')}}</option>

                <option class="select-color" value="{{PROJECT_PUBLISH_STATUS_OPENED}}" @if(Request::get('publish_status') == PROJECT_PUBLISH_STATUS_OPENED && Request::get('publish_status')!='' ) selected @endif>{{tr('PROJECT_PUBLISH_STATUS_OPENED')}}</option>

                <option class="select-color" value="{{PROJECT_PUBLISH_STATUS_CLOSED}}" @if(Request::get('publish_status') == PROJECT_PUBLISH_STATUS_CLOSED && Request::get('publish_status')!='' ) selected @endif>{{tr('PROJECT_PUBLISH_STATUS_CLOSED')}}</option>

                <option class="select-color" value="{{PROJECT_PUBLISH_STATUS_SCHEDULED}}" @if(Request::get('publish_status') == PROJECT_PUBLISH_STATUS_SCHEDULED && Request::get('publish_status')!='' ) selected @endif>{{tr('PROJECT_PUBLISH_STATUS_SCHEDULED')}}</option>

            </select>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-12">

            <div class="input-group">

                <input type="text" class="form-control" name="search_key" value="{{Request::get('search_key')??''}}" placeholder="{{tr('projects_search_placeholder')}}"> 

                <span class="input-group-btn">
                    &nbsp

                    <button type="submit" class="btn btn-default reset-btn">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                    @if(Request::get('user_id'))
                    <a href="{{route('admin.projects.index',['user_id'=>Request::get('user_id') ?? '', ])}}" class="btn btn-default reset-btn">
                        <span> <i class="fa fa-eraser" aria-hidden="true"></i>
                        </span>
                    </a>
                    @else
                    <a href="{{route('admin.projects.index')}}" class="btn btn-default reset-btn">
                        <span> <i class="fa fa-eraser" aria-hidden="true"></i>
                        </span>
                    </a>
                    @endif
                </span>

            </div>

        </div>

    </div>

</form>
<br>