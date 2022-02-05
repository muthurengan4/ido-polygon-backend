@extends('layouts.admin') 

@section('title', tr('subscription_payments')) 

@section('content-header', tr('payments')) 

@section('breadcrumb')

<li class="breadcrumb-item active">
    <a href="{{route('admin.subscription_payments.index')}}">{{ tr('subscription_payments') }}</a>
</li>

<li class="breadcrumb-item active">{{ tr('view_subscription_payments') }}</li>

@endsection 

@section('content')

<div class="row">

    <div class="col-md-12">
     
        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">

                    {{$title ?? tr('view_subscription_payments') }}

                </h3>
                
            </div>

            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <li> All the subscription payment details will be displayed here. (Whenever the project owner purchases a subscription plan - transaction details will be logged here).  </li>
                        </ul>
                    </p>
                </div>

                @include('admin.revenues.subscription_payments._search')

                <div class="table-responsive">
            
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                    
                        <thead>
                            <tr>
                                <th>{{ tr('s_no') }}</th>
                                <th>{{ tr('subscription')}}</th>
                                <th>{{ tr('user_name') }}</th>
                                <th>{{ tr('payment_id')}}</th>
                                <th>{{ tr('amount') }}</th>
                                <th>{{ tr('status') }}</th>
                                <th>{{ tr('action') }}</th>
                            </tr>
                        </thead>
                   
                        <tbody>

                            @foreach($subscription_payments as $i => $subscription_payment)
                            <tr>
                                <td>{{ $i+$subscription_payments->firstItem() }}</td>

                                <td>
                                    <a href="{{ route('admin.subscriptions.view', ['subscription_id' => $subscription_payment->subscription_id] ) }}">{{$subscription_payment->subscription->title ?? tr('not_available')}}</a>
                                </td>

                                <td>
                                    <a href="{{  route('admin.users.view' , ['user_id' => $subscription_payment->user_id] )  }}">
                                        {{ $subscription_payment->user->name ?? tr('not_available')}}
                                    </a>
                                </td>

                                <td> <div style="width: 200px; word-wrap: break-word;"><a class="text-info" href="{{ route('admin.subscription_payments.view', ['subscription_payment_id' => $subscription_payment->id] ) }}">{{$subscription_payment->payment_id ?: tr('not_available')}}</a></div>

                                    <br>
                                    <span class="">{{tr('subscribed_at')}}: {{common_date($subscription_payment->paid_date, Auth::user()->timezone)}}</span>

                                    <br>
                                    <br>
                                    <span class="text-danger">{{tr('expiry')}}: {{common_date($subscription_payment->expiry_date , Auth::guard('admin')->user()->timezone)}}</span>
                                </td>

                                <td>{{ $subscription_payment->amount_formatted}}</td>

                                <td>
                                    @if($subscription_payment->status == PAID)

                                        <span class="label label-success">{{ tr('paid') }}</span>
                                    @else

                                        <span class="label label-warning">{{ tr('pending') }}</span>
                                    @endif
                                </td>


                                <td>

                                    <a class="btn btn-primary" href="{{ route('admin.subscription_payments.view', ['subscription_payment_id' => $subscription_payment->id] ) }}">&nbsp;{{ tr('view') }}</a> 
                                
                                </td>

                            </tr>

                            @endforeach

                        </tbody>
                    
                    </table>

                </div>

            </div>


            <div class="box-footer without-border clearfix">
                    
                <div class="pull-right" id="paglink">{{ $subscription_payments->appends(request()->input())->links() }}</div>
            </div>

        </div>

    </div>
</div>



@endsection