@extends('layouts.admin') 

@section('content-header', tr('faqs')) 

@section('breadcrumb')

<li class="breadcrumb-item active">
    <a href="{{route('admin.faqs.index')}}">{{ tr('faqs') }}</a>
</li>

<li class="breadcrumb-item active">{{ tr('view_faqs') }}</li>
@endsection 

@section('content')


<div class="row">
    
    <div class="col-md-12">

        <div class="box">

            <div class="box-header with-border">
                
                <h3 class="box-title">{{$title ?? tr('view_faqs')}}</h3>

                <div class="heading-elements pull-right">


                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary"><i class="ft-plus icon-left"></i>{{ tr('add_faq') }}</a>

                    
                </div>

            </div>


            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <li>This content will be displayed on the landing page. </li>
                        </ul>
                    </p>
                </div>


                <div class="table-responsive">
                    
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                    
                        <thead>
                            <tr>
                                <th>{{ tr('s_no') }}</th>
                                <th>{{ tr('question') }}</th>
                                <th>{{ tr('status') }}</th>
                                <th>{{ tr('action') }}</th>
                            </tr>
                        </thead>
                   
                        <tbody>

                            @foreach($faqs as $i => $faq)
                            <tr>
                                <td>{{ $i+$faqs->firstItem() }}</td>

                                <td>
                                    <a href="{{route('admin.faqs.view',['faq_id' => $faq->id])}}">
                                    {{ substr($faq->question,0,10)}}...
                                    </a>
                                </td>

                                <td>
                                    @if($faq->status == APPROVED)

                                        <span class="label label-success">{{tr('approved')}}</span>

                                    @else

                                        <span class="label label-danger">{{tr('declined')}}</span>

                                    @endif
                                </td>
                                
                                <td>
                                
                                    <div class="btn-group" role="group">

                                        <button class="btn btn-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> {{ tr('action') }}</button>

                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                            <a class="dropdown-item" href="{{ route('admin.faqs.view', ['faq_id' => $faq->id] ) }}">&nbsp;{{ tr('view') }}</a> 

                                            @if(Setting::get('is_demo_control_enabled') == YES)

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('edit') }}</a>

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('delete') }}</a> 

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.faqs.edit', ['faq_id' => $faq->id] ) }}">&nbsp;{{ tr('edit') }}</a>

                                                <a class="dropdown-item" onclick="return confirm(&quot;{{ tr('faq_delete_confirmation' , $faq->question) }}&quot;);" href="{{ route('admin.faqs.delete', ['faq_id' => $faq->id,'page'=>request()->input('page')] ) }}">&nbsp;{{ tr('delete') }}</a>

                                            @endif

                                            @if($faq->status == APPROVED)

                                                <a class="dropdown-item" href="{{  route('admin.faqs.status' , ['faq_id' => $faq->id] )  }}" onclick="return confirm(&quot;{{ $faq->question }} - {{ tr('faq_decline_confirmation') }}&quot;);">&nbsp;{{ tr('decline') }}
                                            </a> 

                                            @else

                                                <a class="dropdown-item" href="{{ route('admin.faqs.status' , ['faq_id' => $faq->id] ) }}">&nbsp;{{ tr('approve') }}</a> 

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
                    
                <div class="pull-right rd-flex">{{ $faqs->appends(request()->input())->links('pagination::bootstrap-4') }}</div>
            </div>

        </div>

    </div>
</div>


@endsection


