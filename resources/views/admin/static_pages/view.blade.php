@extends('layouts.admin') 

@section('title', tr('view_static_page'))

@section('content-header',tr('static_pages'))

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{route('admin.static_pages.index')}}">{{tr('static_pages')}}</a></li>

    <li class="breadcrumb-item active" aria-current="page">
        <span>{{tr('view_static_page')}}</span>
    </li>
           
@endsection  

@section('content')

    
<div class="row match-height">

    <div class="col-lg-6 col-12">
        
        <div class="box">
            
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover no-margin">
                        <tbody>
                            <tr>
                                <th>{{tr('page_title')}}</th>
                                <td>{{$static_page->title}}</td>
                            </tr>
                            <tr>
                                <th>{{tr('static_page_type')}}</th>
                                <td>{{$static_page->type}}</td>
                            </tr>
                            
                            <tr>
                                <th>{{tr('status')}}</th>
                                <td class="text-capitalize">
                                    @if($static_page->status == APPROVED)

                                        <span class="badge badge-success badge-md text-uppercase">{{tr('approved')}}</span>

                                    @else 

                                        <span class="badge badge-danger badge-md text-uppercase">{{tr('pending')}}</span>

                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <th>{{tr('created_at')}}</th>
                                <td>{{ common_date($static_page->created_at,Auth::guard('admin')->user()->timezone) }}</td>
                            </tr>

                            <tr>
                                <th>{{tr('updated_at')}}</th>
                                <td>{{ common_date($static_page->updated_at,Auth::guard('admin')->user()->timezone) }}</td>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
         </div>
    </div>

    <div class="col-lg-6 col-12">

        <div class="box">
            
            <div class="box-body">

                <div class="media-list media-list-divided">

                    <div class="media media-single">

                        @if($static_page->status == APPROVED)

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{  route('admin.static_pages.status' , ['static_page_id' => $static_page->id] )  }}" onclick="return confirm(&quot;{{ $static_page->title }} - {{ tr('static_page_decline_confirmation') }}&quot;);"> <i class="fa fa-check"></i>  {{ tr('decline') }}
                                                </a>
                            </div>

                        @else

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{ route('admin.static_pages.status' , ['static_page_id' => $static_page->id] ) }}"> <i class="fa fa-check"></i>  {{ tr('approve') }}</a>
                            </div>

                        @endif

                        @if(Setting::get('is_demo_control_enabled') == YES)

                            <div class="media-right">
                                <button class="btn bg-purple margin"><i class="fa fa-edit"></i> {{tr('edit')}}</button>
                            </div>

                            <div class="media-right">
                                <button class="btn bg-olive margin"><i class="fa fa-trash"></i> {{tr('delete')}}</button>
                            </div>

                        @else

                            <div class="media-right">

                                <a class="btn bg-purple margin" href="{{ route('admin.static_pages.edit', ['static_page_id' => $static_page->id] ) }}"><i class="fa fa-edit"></i> {{tr('edit')}}</a>
                            </div>  

                            <div class="media-right">

                                <a class="btn bg-olive margin" onclick="return confirm(&quot;{{ tr('static_page_delete_confirmation' , $static_page->title) }}&quot;);" href="{{ route('admin.static_pages.delete', ['static_page_id' => $static_page->id] ) }}"><i class="fa fa-trash"></i> {{tr('delete')}}</a>
                            </div>

                           

                        @endif

                    </div>
                </div>
            </div>

            <div class="box-body">

                <h4 class="box-title">{{ tr('description') }}</h4>
        
                <p class="card-text"><?= $static_page->description ?></p>
                
            </div>

        </div>
    </div>

</div>


@endsection
