@extends('layouts.admin')

@section('content-header')

Transactions

@endsection

@section('breadcrumb')

<a href="{{route('admin.transactions.getaccount')}}" class="btn px-15 btn-primary">
	<i class="las la-eye fs-16"></i> Transcations
</a>

@endsection

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header color-dark fw-500">
                List Of Transactions
            </div>
            <div class="card-body p-0">
                <div class="table4  p-25 bg-white mb-30">
                    <div class="table-responsive">

                        @if($transactions)
                        
                        <table class="table mb-0">
                            <thead>
                                <tr class="userDatatable-header">
                                    <th>
                                        <span class="userDatatable-title">S.No</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">From | To</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">Value</span>
                                    </th>
                                    <!-- <th>
                                        <span class="userDatatable-title">Gas Value</span>
                                    </th> -->
                                    <th>
                                        <span class="userDatatable-title">Service Fee</span>
                                    </th>

                                    <th>
                                        <span class="userDatatable-title">Method</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($transactions as $i => $transaction)

                                <tr>
                                    <td>
                                        <div class="userDatatable-content">
                                            {{$i+1}}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="userDatatable-content">
                                            From: {{$transaction->from}}
                                            <br>
                                            To: {{$transaction->to}}

                                        </div>
                                    </td>
                                    <td>
                                        <div class="userDatatable-content">
                                            {{bcdiv($transaction->value,'1000000000000000000', 4)}} Ether
                                        </div>
                                    </td>
                                 
                                    <td>
                                        <div class="userDatatable-content">
                                            {{bcdiv($transaction->gas * $transaction->gasPrice,'1000000000000000000', 6)}}
                                        </div>
                                    </td>

                                    <td>
                                        Actions
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>
                        
                        </table>

                        @else

                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection