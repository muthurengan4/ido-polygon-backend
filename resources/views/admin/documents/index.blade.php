@extends('layouts.admin') 

@section('title', tr('documents')) 

@section('content-header', tr('documents')) 

@section('breadcrumb')
    
<li class="breadcrumb-item active">
    <a href="{{route('admin.documents.index')}}">{{ tr('documents') }}</a>
</li>

<li class="breadcrumb-item active">{{ tr('view_documents') }}</li>

@endsection 

@section('content')

<div class="row">
    
    <div class="col-md-12">
            
        <p>{{tr('documents_list_note')}} <a href="{{route('admin.user_documents.index')}}">{{tr('click_here_documents')}}</a></p>

        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">{{$title ?? tr('view_documents')}}</h3>
            
                    <div class="heading-elements pull-right">
                        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">{{ tr('add_document') }}</a>
                    </div>
                    
            </div>

            <div class="box-body">

                <div class="table-responsive">
                    
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                            
                        <thead>
                            <tr>
                                <th>{{ tr('s_no') }}</th>
                                <th>{{ tr('name') }}</th>
                                <th>{{ tr('status') }}</th>
                                <th>{{ tr('is_required') }}</th>
                                <th>{{ tr('action') }}</th>
                            </tr>
                        </thead>
                           
                        <tbody>

                            @foreach($documents as $i => $document)
                            <tr>
                                <td>{{ $i+1 }}</td>

                                <td>
                                    <a href="{{  route('admin.documents.view' , ['document_id' => $document->id] )  }}">
                                    {{ $document->name }}
                                    </a>
                                </td>

                                <td>
                                    @if($document->status == APPROVED)

                                        <span class="label label-success">{{ tr('approved') }}</span>
                                    @else

                                        <span class="label label-warning">{{ tr('declined') }}</span> 
                                    @endif
                                </td>

                                <td>
                                    @if($document->is_required == YES)

                                   <span class="label label-success">{{ tr('yes') }}</span>

                                    @else

                                    <span class="label label-danger">{{ tr('no') }}</span> @endif
                                </td>

                                <td>
                                
                                    <div class="btn-group" role="group">

                                        <button class="btn btn-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> {{ tr('action') }}</button>

                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                            <a class="dropdown-item" href="{{ route('admin.documents.view', ['document_id' => $document->id] ) }}">&nbsp;{{ tr('view') }}</a> 

                                            @if(Setting::get('is_demo_control_enabled') == YES)

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('edit') }}</a>

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('delete') }}</a> 

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.documents.edit', ['document_id' => $document->id] ) }}">&nbsp;{{ tr('edit') }}</a>

                                                <a class="dropdown-item" onclick="return confirm(&quot;{{ tr('document_delete_confirmation' , $document->name) }}&quot;);" href="{{ route('admin.documents.delete', ['document_id' => $document->id,'page'=>request()->input('page')] ) }}">&nbsp;{{ tr('delete') }}</a>

                                            @endif

                                            @if($document->status == APPROVED)

                                                <a class="dropdown-item" href="{{  route('admin.documents.status' , ['document_id' => $document->id] )  }}" onclick="return confirm(&quot;{{ $document->name }} - {{ tr('document_decline_confirmation') }}&quot;);">&nbsp;{{ tr('decline') }}
                                            </a> 

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.documents.status' , ['document_id' => $document->id] ) }}">&nbsp;{{ tr('approve') }}</a> 

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
                    
                <div class="pull-right rd-flex">{{ $documents->appends(request()->input())->links('pagination::bootstrap-4') }}</div>
            </div>


        </div>

    </div>

</section>

@endsection