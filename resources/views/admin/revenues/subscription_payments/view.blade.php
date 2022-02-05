@extends('layouts.admin') 

@section('content-header', tr('payments')) 

@section('breadcrumb')

    
<li class="breadcrumb-item">
    <a href="{{route('admin.subscription_payments.index')}}">{{ tr('subscription_payments') }}</a>
</li>

<li class="breadcrumb-item active">{{tr('view_subscription_payments')}}</li>

@endsection 

@section('content')

<div class="row">   

    <div class="col-md-12">
         
        <div class="box">

            <div class="box-header without-border">

                <h4 class="card-title">{{ tr('view_subscription_payments') }}</h4>
                
            </div>

            <div class="box-body">

                <div class="row">
                    
                    <div class="col-md-6">

                        <table class="table table-bordered table-striped tab-content table-responsive-sm">
               
                            <tbody>

                                <tr>
                                    <td>{{ tr('unique_id')}} </td>
                                    <td class="text-uppercase">{{ $subscription_payment->unique_id ?: tr('not_available')}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('payment_id')}} </td>
                                    <td><div style="width: 200px; word-wrap: break-word;">{{ $subscription_payment->payment_id ?: tr('not_available')}}</div></td>
                                </tr>

                                <tr>
                                    <td>{{ tr('plan')}} </td>
                                    <td>{{ $subscription_payment->plan_formatted}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('subscription_title')}} </td>
                                    <td><a href="{{route('admin.subscriptions.view',['subscription_id' => $subscription_payment->subscription_id])}}">{{ $subscription_payment->subscription->title ?? tr('not_available')}}</a></td>
                                </tr>

                                <tr>
                                    <td>{{ tr('payment_mode')}} </td>
                                    <td>{{ $subscription_payment->payment_mode ?: tr('not_available')}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('user_name')}} </td>
                                    <td>
                                        <a href="{{ route('admin.users.view', ['user_id' => $subscription_payment->user_id])}}">
                                        {{ $subscription_payment->user->name ?? tr('not_available')}}
                                        </a>
                                    </td>
                                </tr> 

                                <tr>
                                    <td>{{ tr('amount') }}</td>
                                    <td>{{ $subscription_payment->amount_formatted}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('is_current_subscription') }}</td>
                                    <td>
                                        @if($subscription_payment->is_current_subscription ==YES)

                                            <span class="badge bg-success">{{tr('yes')}}</span>

                                        @else 
                                            <span class="badge bg-danger">{{tr('no')}}</span>

                                        @endif
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>

                    <div class="col-md-6">
                        
                        <table class="table table-bordered table-striped tab-content table-responsive-sm">
               
                            <tbody>

                                <tr>
                                    <td>{{ tr('expiry_date') }}</td>
                                    <td>{{common_date($subscription_payment->expiry_date , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('paid_date') }}</td>
                                    <td>{{common_date($subscription_payment->paid_date , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('status') }}</td>
                                    <td>
                                        @if($subscription_payment->status ==YES)

                                            <span class="badge bg-success">{{tr('paid')}}</span>

                                        @else 
                                            <span class="badge bg-danger">{{tr('unpaid')}}</span>

                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ tr('is_cancelled') }}</td>
                                    <td>
                                        @if($subscription_payment->is_cancelled ==YES)

                                            <span class="badge bg-success">{{tr('yes')}}</span>

                                        @else 
                                            <span class="badge bg-danger">{{tr('no')}}</span>

                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ tr('cancel_reason') }}</td>
                                    <td>{{ $subscription_payment->cancel_reason ?: tr('not_available')}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('created_at') }}</td>
                                    <td>{{common_date($subscription_payment->created_at , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('updated_at') }}</td>
                                    <td>{{common_date($subscription_payment->updated_at , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection