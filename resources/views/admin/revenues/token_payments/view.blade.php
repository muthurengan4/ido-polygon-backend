@extends('layouts.admin')

@section('content-header', tr('payments'))

@section('breadcrumb')

<li class="breadcrumb-item"><a href="">{{ tr('payments') }}</a></li>

<li class="breadcrumb-item">
    <a href="{{ route('admin.token_payments.index') }}">{{ tr('token_payments') }}</a>
</li>

<li class="breadcrumb-item active" aria-current="page">
    <span>{{ tr('view_token_payments') }}</span>
</li>

@endsection

@section('content')

<div class="row">   

    <div class="col-md-12">
         
        <div class="box">

            <div class="box-header without-border">

                <h4 class="card-title">{{ tr('view_token_payments') }}</h4>
            </div>

            <div class="box-body">

                <div class="row">
                    
                    <div class="col-md-8">

                        <table class="table table-bordered table-striped tab-content table-responsive-sm">

                            <tbody>

                                <tr>
                                    <td>{{ tr('unique_id')}} </td>
                                    <td class="text-uppercase">{{ $token_payment->unique_id}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('username')}} </td>
                                    <td>
                                        <a href="{{route('admin.users.view',['user_id'=>$token_payment->user_id ?? ''])}}">
                                            {{ $token_payment->username ?? tr('not_available')}}
                                        </a>
                                    </td>
                                </tr>


                                <tr>
                                    <td>{{ tr('payment_id')}} </td>
                                    <td>{{ $token_payment->from_payment_id}}</td>
                                </tr>

                              

                                <tr>
                                    <td>{{ tr('purchased')}} </td>
                                    <td>{{ $token_payment->purchased_formatted}}</td>
                                </tr>


                                <tr>
                                    <td>{{ tr('from_wallet_address')}} </td>
                                    <td>{{ $token_payment->from_wallet_address}}</td>
                                </tr>



                            </tbody>

                        </table>

                    </div>

                    <div class="col-md-4">

                        <table class="table table-bordered table-striped tab-content table-responsive-sm">

                            <tbody>

                                <tr>
                                    <td>{{ tr('paid_date') }}</td>
                                    <td>{{common_date($token_payment->created_at , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('status') }}</td>
                                    <td>
                                        @if($token_payment->status ==YES)

                                        <span class="label label-success">{{tr('paid')}}</span>

                                        @else
                                        <span class="label label-danger">{{tr('not_paid')}}</span>

                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ tr('created_at') }}</td>
                                    <td>{{common_date($token_payment->created_at , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('updated_at') }}</td>
                                    <td>{{common_date($token_payment->updated_at , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection