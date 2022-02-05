@extends('layouts.admin')

@section('content-header', tr('payments'))

@section('breadcrumb')

<li class="breadcrumb-item"><a href="">{{ tr('payments') }}</a></li>

<li class="breadcrumb-item">
    <a href="{{ route('admin.project_payments.index') }}">{{ tr('project_payments') }}</a>
</li>

<li class="breadcrumb-item active" aria-current="page">
    <span>{{ tr('view_project_payment') }}</span>
</li>

@endsection

@section('content')

<div class="row">   

    <div class="col-md-12">
         
        <div class="box">

            <div class="box-header without-border">

                <h4 class="card-title">{{ tr('view_project_payment') }}</h4>
            </div>

            <div class="box-body">

                <div class="row">
                    
                    <div class="col-md-8">

                        <table class="table table-bordered table-striped tab-content table-responsive-sm">

                            <tbody>

                                <tr>
                                    <td>{{ tr('unique_id')}} </td>
                                    <td class="text-uppercase">{{ $project_payment->unique_id}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('project')}} </td>
                                    <td>
                                        <a href="{{route('admin.projects.view',['project_id'=>$project_payment->project_id ?? ''])}}">
                                            {{ $project_payment->project->name ?? tr('not_available')}}
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ tr('user')}} </td>
                                    <td>
                                        <a href="{{route('admin.users.view',['user_id'=>$project_payment->user_id ?? ''])}}">
                                            {{ $project_payment->user->name ?? tr('not_available')}}
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ tr('from_payment_id')}} </td>
                                    <td>{{ $project_payment->from_payment_id}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('wallet_address_from')}} </td>
                                    <td>{{ $project_payment->from_wallet_address}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('to_payment_id')}} </td>
                                    <td>{{ $project_payment->to_payment_id}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('to_wallet_address')}} </td>
                                    <td>{{ $project_payment->to_wallet_address}}</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="col-md-4">

                        <table class="table table-bordered table-striped tab-content table-responsive-sm">

                            <tbody>

                                <tr>
                                    <td>{{ tr('purchased')}} </td>
                                    <td>{{ formatted_amount($project_payment->purchased) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('confirmed')}} </td>
                                    <td>{{ formatted_amount($project_payment->confirmed) }}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('paid_date') }}</td>
                                    <td>{{common_date($project_payment->created_at , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('status') }}</td>
                                    <td>
                                        @if($project_payment->status ==YES)

                                        <span class="label label-success">{{tr('paid')}}</span>

                                        @else
                                        <span class="label label-danger">{{tr('not_paid')}}</span>

                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>{{ tr('created_at') }}</td>
                                    <td>{{common_date($project_payment->created_at , Auth::guard('admin')->user()->timezone)}}</td>
                                </tr>

                                <tr>
                                    <td>{{ tr('updated_at') }}</td>
                                    <td>{{common_date($project_payment->updated_at , Auth::guard('admin')->user()->timezone)}}</td>
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