<form class="col-6 row pull-right" action="{{route('admin.invested_projects')}}" method="GET" role="search">
    <input type="hidden" id="user_id" name="user_id" value="{{Request::get('user_id') ?? ''}}">
    <div class="input-group">
        <input type="text" class="form-control" name="search_key"  value="{{Request::get('search_key')??''}}"
        placeholder="{{tr('invested_projects_search_placeholder')}}" required> 
        <span class="input-group-btn">
            &nbsp
            <button type="submit" class="btn btn-default">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
            @if(Request::get('user_id'))
                <a href="{{route('admin.invested_projects',['user_id'=>Request::get('user_id') ?? '', ])}}" class="btn btn-default reset-btn">
                    <span> <i class="fa fa-eraser" aria-hidden="true"></i>
                    </span>
                </a>
            @else
            <a href="{{route('admin.invested_projects')}}" class="btn btn-default reset-btn">
                <span class=""> <i class="fa fa-eraser" aria-hidden="true"></i>
                </span>
            </a>
            @endif
        </span>
    </div>
</form>