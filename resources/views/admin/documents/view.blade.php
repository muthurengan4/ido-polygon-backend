@extends('layouts.admin')

@section('title', tr('view_documents'))

@section('content-header', tr('view_documents'))

@section('breadcrumb')

<li class="breadcrumb-item">
    <a href="{{route('admin.documents.index')}}">{{tr('documents')}}</a>
</li>

<li class="breadcrumb-item active">{{tr('view_documents')}}</li>

@endsection

@section('content')

<div class="box">
    
    <div class="box-body">

        <div class="row">
            
            <div class="col-md-12">
                <div class="media-list media-list-divided">

                    <div class="media media-single">

                        <img class="w-80" src="{{$document->picture}}" alt="...">
                        <div class="media-body">
                            <h6>{{$document->name}}</h6>
                            <small class="text-fader">{{common_date($document->created_at , Auth::guard('admin')->user()->timezone)}}</small>
                        </div>


                        @if($document->status == APPROVED)

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{  route('admin.documents.status' , ['document_id' => $document->id] )  }}" onclick="return confirm({{ tr('document_delete_confirmation') }}&quot;);"> <i class="fa fa-check"></i>  {{ tr('decline') }}
                                                </a>
                            </div>

                        @else

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{ route('admin.documents.status' , ['document_id' => $document->id] ) }}"> <i class="fa fa-check"></i>  {{ tr('approve') }}</a>
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

                                <a class="btn bg-purple margin" href="{{ route('admin.documents.edit', ['document_id' => $document->id] ) }}"><i class="fa fa-edit"></i> {{tr('edit')}}</a>
                            </div>  

                            <div class="media-right">

                                <a class="btn bg-olive margin" onclick="return confirm(&quot;{{ tr('user_delete_confirmation' , $document->name) }}&quot;);" href="{{ route('admin.documents.delete', ['user_id' => $document->id] ) }}"><i class="fa fa-trash"></i> {{tr('delete')}}</a>
                            </div>

                           

                        @endif

                        
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>      

<div class="row">
    
    <div class="col-lg-12 col-12">
        
        <div class="box">
            
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover no-margin">
                        <tbody>
                            <tr>
                                <th>{{tr('name')}}</th>
                                <td>{{$document->name}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('is_required')}}</th>
                                <td>
                                    @if($document->is_required == YES)
                                        <span class="badge bg-success">{{tr('yes')}}</span>
                                    @else
                                        <span class="badge bg-danger">{{tr('no')}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{tr('status')}}</th>
                                <td>
                                    @if($document->status == APPROVED)
                                        <span class="badge bg-success">{{tr('approved')}}</span>
                                    @else
                                        <span class="badge bg-danger">{{tr('declined')}}</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{tr('created_at')}}</th>
                                <td>{{common_date($document->created_at , Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('updated_at')}}</th>
                                <td>{{common_date($document->updated_at , Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>

                            <tr>
                                <th>{{tr('description')}}</th>
                                <td>{{$document->description ?: "-"}}</td>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
         </div>
    </div>
    
</div>
  
@endsection

