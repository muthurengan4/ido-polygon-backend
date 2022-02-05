@extends('layouts.admin')

@section('content-header', tr('payments'))

@section('breadcrumb')

<li class="breadcrumb-item"><a href="">{{ tr('payments') }}</a></li>

<li class="breadcrumb-item active" aria-current="page">
    <span>{{ tr('token_payments') }}</span>
</li>

@endsection

@section('content')

<div class="row">

    <div class="col-md-12">
     
        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">{{ tr('token_payments') }} 

                    @if(Request::get('user_id'))
                    - 
                        <a href="{{route('admin.users.view',['user_id'=>$user->id ?? ''])}}">{{$user->name ?? ''}}</a>
                    @endif
                    
                </h3>

                    
            </div>

            <div class="box-body">

                @include('admin.revenues.token_payments._search')

                <div class="table-responsive">
        
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">

                        <thead>
                            <tr>
                                <th>{{tr('s_no')}}</th>
                                <th>{{tr('username')}}</th>
                                <th>{{tr('transaction')}}</th>
                                <th>{{tr('purchased')}}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($token_payments as $i => $token_payment)

                            <tr>
                                <td>{{$i+$token_payments->firstItem()}}</td>

                                <td>
                                    <a href="{{route('admin.users.view' , ['user_id' => $token_payment->user_id])}}"> {{ $token_payment->username ?:tr('not_available')}}
                                    </a>
                                </td>

                                <td>
                                    {{tr('hash')}}: <span class="text-success">{{$token_payment->from_payment_id ?? tr('not_available')}}</span>

                                    <br>
                                    <br>
                                    <span class="text-gray">{{tr('from_wallet_address')}}: {{$token_payment->from_wallet_address ?? tr('not_available')}}</span>

                                </td>
                                    
                                <td>{{ $token_payment->purchased_formatted }}</td>

                                <td>

                                    @if($token_payment->status == PAID)

                                    <span class="label label-success">{{ tr('paid') }} </span>

                                    @else

                                    <span class="label label-danger">{{ tr('not_paid') }} </span>

                                    @endif

                                </td>


                                <td>

                                    <a class="btn btn-primary" href="{{ route('admin.token_payments.view', ['token_payment_id' => $token_payment->id] ) }}">&nbsp;{{ tr('view') }}</a> 

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>


                </div>

            </div>


            <div class="box-footer without-border clearfix">
                    
                <div class="pull-right resp-float-unset" id="paglink">{{ $token_payments->appends(request()->input())->links() }}</div>
            </div>

        </div>

    </div>
</div>

@endsection