@extends('layouts.admin')

@section('title', tr('subscriptions'))

@section('icon', 'home')

@section('content-header', tr('view_subscription'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.subscriptions.index')}}">{{ tr('subscriptions') }}</a>
    </li>

    <li class="breadcrumb-item"><a>{{tr('view_subscription')}}</a> </li>

@endsection

@section('content')

<div class="box">
    
    <div class="box-body">

        <div class="row">
            
            <div class="col-md-12">
                <div class="media-list media-list-divided">

                    <div class="media media-single">

                        <img class="w-80" src="{{$subscription->picture}}" alt="...">

                        <div class="media-body">

                            <h6>{{$subscription->title ?: tr('not_available')}}</h6>

                            <small class="text-fader">{{common_date($subscription->created_at , Auth::guard('admin')->user()->timezone)}}</small>
                        </div>


                        @if($subscription->status == APPROVED)

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{  route('admin.subscriptions.status' , ['subscription_id' => $subscription->id] )  }}" onclick="return confirm(&quot;{{ $subscription->title ?: tr('not_available') }} - {{ tr('subscription_decline_confirmation') }}&quot;);">
                                    <i class="fa fa-check"></i> {{ tr('decline') }}
                                </a>
                            </div>

                        @else

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{ route('admin.subscriptions.status' , ['subscription_id' => $subscription->id] ) }}"> <i class="fa fa-check"></i>  {{ tr('approve') }}</a>
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

                                <a class="btn bg-purple margin" href="{{ route('admin.subscriptions.edit', ['subscription_id' => $subscription->id] ) }}"><i class="fa fa-edit"></i> {{tr('edit')}}</a>
                            </div>  

                            <div class="media-right">

                                <a class="btn bg-olive margin" onclick="return confirm(&quot;{{ tr('subscription_delete_confirmation' , $subscription->title ?: tr('not_available')) }}&quot;);" href="{{ route('admin.subscriptions.delete', ['subscription_id' => $subscription->id,'page'=>request()->input('page')] ) }}"><i class="fa fa-trash"></i> {{tr('delete')}}</a>
                            </div>

                        @endif
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>   

<div class="row">   

    <div class="col-md-12">
         
        <div class="box">

            <div class="box-header with-border">


                <div class="box-body">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="table-responsive">

                                <table class="table table-xl mb-0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <h6>{{ tr('subscription_title') }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-c-blue text-capitalize">{{$subscription->title ?: tr('not_available')}}</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <h6>{{ tr('created_at') }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-c-blue text-capitalize">
                                            {{common_date($subscription->created_at , Auth::guard('admin')->user()->timezone)}}

                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <h6>{{ tr('updated_at') }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-c-blue text-capitalize">
                                            {{common_date($subscription->updated_at , Auth::guard('admin')->user()->timezone)}}

                                            </td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>
                        
                        </div>

                        <div class="col-md-6">

                            <div class="table-responsive">

                                <table class="table table-xl mb-0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <h6>{{ tr('min_staking_balance') }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-c-blue text-capitalize">{{$subscription->min_staking_balance ?: 0}}</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <h6>{{ tr('allowed_tokens') }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-c-blue text-capitalize">{{$subscription->allowed_tokens ?: 0}}</td>
                                        </tr>

                                         <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <h6>{{ tr('status') }}</h6>
                                                </div>
                                            </td>
                                           <td>

                                            @if($subscription->status == APPROVED)

                                                <span class="badge badge-success">{{ tr('approved') }} </span>

                                            @else

                                                <span class="badge badge-danger">{{ tr('declined') }} </span>

                                            @endif

                                        </td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>
                        
                        </div>

                        <div class="col-md-12">
                            <hr />
                            <h6>{{ tr('description') }}</h6>
                            <p class="">{{$subscription->description ?: tr('not_available')}}</p>
                        </div>

                    </div>

                </div>
            
            </div>
            
        </div>
    </div>
</div>
@endsection