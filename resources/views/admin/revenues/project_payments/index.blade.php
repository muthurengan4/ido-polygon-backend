@extends('layouts.admin')

@section('content-header', tr('payments'))

@section('breadcrumb')

<li class="breadcrumb-item"><a href="">{{ tr('payments') }}</a></li>

<li class="breadcrumb-item active" aria-current="page">
    <span>{{ tr('project_owner_payments') }}</span>
</li>

@endsection

@section('content')

<div class="row">

    <div class="col-md-12">
     
        <div class="box">

            <div class="box-header with-border">

                <h3 class="box-title">{{ tr('project_owner_payments') }} 

                    @if(Request::get('user_id'))
                    - 
                        <a href="{{route('admin.users.view',['user_id'=>$user->id ?? ''])}}">{{$user->name ?? ''}}</a>
                    @endif
                    
                </h3>

                    
            </div>

            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <li>All the project owner transactions will be displayed here. </li>
                        </ul>
                    </p>
                </div>

                @include('admin.revenues.project_payments._search')

                <div class="table-responsive">
        
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">

                        <thead>
                            <tr>
                                <th>{{tr('s_no')}}</th>
                                <th>{{tr('project_name')}}</th>
                                <th>{{tr('user_name')}}</th>
                                <th>{{tr('wallet_address_from')}}</th>
                                <!-- <th>{{tr('to_wallet_address')}}</th> -->
                                <th>{{tr('purchased')}}</th>
                                <th>{{tr('confirmed')}}</th>
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($project_payments as $i => $project_payment)

                                <tr>
                                    <td>{{$i+$project_payments->firstItem()}}</td>

                                    <td>
                                        <a href="{{route('admin.projects.view' , ['project_id' => $project_payment->project_id])}}"> {{ $project_payment->project->name ?? tr('not_available')}}
                                        </a>
                                    </td>

                                    <td><a href="{{route('admin.users.view' , ['user_id' => $project_payment->user_id])}}"> {{ $project_payment->user->name ?? tr('not_available') }}</a></td>

                                    <td>
                                        <span class="text-info"> {{ $project_payment->from_wallet_address ?: tr('not_available')}}</span>
                                    </td>

                                    <!-- <td>{{ $project_payment->to_wallet_address ?: tr('not_available') }}</td> -->

                                    <td>{{ formatted_amount($project_payment->purchased) }}</td>

                                    <td>{{ formatted_amount($project_payment->confirmed) }}</td>

                                    <td>

                                        <a class="btn btn-primary" href="{{ route('admin.project_payments.view', ['project_payment_id' => $project_payment->id] ) }}">&nbsp;{{ tr('view') }}</a> 

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>


                </div>

            </div>


            <div class="box-footer without-border clearfix">
                    
                <div class="pull-right resp-float-unset" id="paglink">{{ $project_payments->appends(request()->input())->links() }}</div>
            </div>

        </div>

    </div>
</div>

@endsection