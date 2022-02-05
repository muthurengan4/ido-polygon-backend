@extends('layouts.admin') 

@section('content-header', tr('static_pages'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('admin.static_pages.index' )}}">{{tr('static_pages')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{ tr('view_static_pages') }}</span>
    </li>
           
@endsection 

@section('content')

<div class="row">
    
    <div class="col-md-12">

        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">{{ tr('view_static_pages') }}</h3>

                <div class="heading-elements pull-right">
                    <a href="{{ route('admin.static_pages.create') }}" class="btn btn-primary"><i class="ft-plus icon-left"></i>{{ tr('add_static_page') }}</a>
                </div>
                    
            </div>

            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <li>You can add content to your pages like terms and conditions, Privacy policy, etc,.</li>
                        </ul>
                    </p>
                </div>

                <div class="table-responsive">
                    
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>{{tr('s_no')}}</th>
                                <th>{{tr('page_title')}}</th>
                                <th>{{tr('static_page_type')}}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>

                        <tbody>


                            @foreach($static_pages as $i => $static_page)

                                <tr>
                                    <td>{{$i+$static_pages->firstItem()}}</td>

                                    <td>
                                        <a href="{{route('admin.static_pages.view' , ['static_page_id'=> $static_page->id] )}}"> {{$static_page->title}}</a>
                                    </td>

                                    <td class="text-capitalize">{{$static_page->type}}</td>

                                    <td>
                                        @if($static_page->status == APPROVED)

                                          <span class="label label-success">{{tr('approved')}}</span> 

                                        @else

                                          <span class="label label-warning">{{tr('pending')}}</span> 
                                        @endif
                                    </td>

                                    <td>  

                                        <div class="btn-group" role="group">

                                            <button class="btn btn-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> {{ tr('action') }}</button>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuOutlineButton1">

                                                <a class="dropdown-item" href="{{ route('admin.static_pages.view', ['static_page_id' => $static_page->id] ) }}">
                                                    {{tr('view')}}
                                                </a>

                                                @if(Setting::get('is_demo_control_enabled') == NO)
                                                
                                                    <a class="dropdown-item" href="{{ route('admin.static_pages.edit', ['static_page_id' => $static_page->id] ) }}">
                                                        {{tr('edit')}}
                                                    </a>

                                                    <a class="dropdown-item" 
                                                    onclick="return confirm(&quot;{{tr('static_page_delete_confirmation' , $static_page->title)}}&quot;);" href="{{ route('admin.static_pages.delete', ['static_page_id' => $static_page->id,'page'=>request()->input('page')] ) }}" >
                                                        {{ tr('delete') }}
                                                    </a>

                                                @else

                                                    <a class="dropdown-item text-muted" href="javascript:;">{{tr('edit')}}</a>

                                                    <a class="dropdown-item text-muted" href="javascript:;">{{ tr('delete') }}</a>

                                                @endif                  

                                                <div class="dropdown-divider"></div>

                                                @if($static_page->status == APPROVED)

                                                    <a class="dropdown-item" href="{{ route('admin.static_pages.status', ['static_page_id' =>  $static_page->id] ) }}" 
                                                    onclick="return confirm(&quot;{{$static_page->title}} - {{tr('static_page_decline_confirmation')}}&quot;);"> 
                                                        {{tr('decline')}}
                                                    </a>

                                                @else

                                                    <a class="dropdown-item" href="{{ route('admin.static_pages.status', ['static_page_id' =>  $static_page->id] ) }}">
                                                        {{tr('approve')}}
                                                    </a>
                                                       
                                                @endif

                                            </div>
                                             
                                        </div>
                                    

                                    </td>
                                
                                </tr>

                            @endforeach

                        </tbody>
                        
                    </table>

                </div>

            </div>

            <div class="box-footer clearfix">
                    
                <div class="pull-right rd-flex">{{ $static_pages->appends(request()->input())->links('pagination::bootstrap-4') }}</div>
            </div>

        </div>

    </div>

</section>

@endsection