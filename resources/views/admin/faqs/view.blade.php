@extends('layouts.admin')

@section('title', tr('faqs'))

@section('content-header', tr('faqs'))

@section('breadcrumb')
    

    <li class="breadcrumb-item"><a href="{{route('admin.faqs.index')}}">{{tr('faqs')}}</a>
    </li>

    <li class="breadcrumb-item active">{{tr('view_faqs')}}</a>
    </li>

@endsection

@section('content')

<div class="box">
    <div class="box-body">
        
        <div>
            <h4>{{tr('view_faqs')}}</h4>
            <hr>
            <h5>{{tr('question')}} : <br> {{$faq->question}}</h5>

            <h5>{{tr('answer')}} : <br> <?= $faq->answer ?></h5>

            <hr>
        </div>
        <div>
            <h6 class="text-bold">{{tr('created_at')}} - {{ common_date($faq->created_at,Auth::guard('admin')->user()->timezone) }}</h6>
            <h6 class="text-bold">{{tr('updated_at')}} - {{ common_date($faq->updated_at,Auth::guard('admin')->user()->timezone) }}</h6>
        </div>

        <div class="text-right mt-30">

            @if(Setting::get('is_demo_control_enabled') == NO)

                <div class="btn btn-info">

                    <a href="{{ route('admin.faqs.edit', ['faq_id'=> $faq->id] ) }}" > {{tr('edit')}} </a>
                    
                </div>                              

                <div class="btn btn-warning">
                    <a onclick="return confirm(&quot;{{tr('faq_delete_confirmation' , $faq->question)}}&quot;);" href="{{ route('admin.faqs.delete', ['faq_id'=> $faq->id] ) }}">
                        {{ tr('delete') }}
                    </a>

                </div>                               

            @else
            
                <div class="btn btn-primary">{{tr('edit')}}</div>
                <div class="btn btn-success">{{ tr('delete') }}</div>
                

            @endif

            @if($faq->status == APPROVED)

                <div class="btn btn-primary">
                    
                    <a href="{{ route('admin.faqs.status', ['faq_id'=> $faq->id] ) }}" onclick="return confirm(&quot;{{ $faq->question }}-{{tr('faq_decline_confirmation' , $faq->title)}}&quot;);">

                        {{tr('decline')}}
                    </a>
                </div>

            @else

                <div class="btn btn-primary">
                     <a href="{{ route('admin.faqs.status', ['faq_id'=> $faq->id] ) }}">
                        {{tr('approve')}}
                    </a>
                </div>
                   
            @endif

        </div>
    </div>
</div>   
@endsection

