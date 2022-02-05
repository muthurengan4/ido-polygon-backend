@extends('layouts.admin')

@section('title', tr('subscriptions'))

@section('content-header', tr('subscriptions'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.subscriptions.index')}}">{{ tr('subscriptions') }}</a>
    </li>

    <li class="breadcrumb-item">{{tr('view_subscriptions')}}</li>

@endsection

@section('content')


<div class="row">   

    <div class="col-md-12">
         
        <div class="box">

            <div class="box-header with-border">
                <h3 class="box-title">{{ tr('view_subscriptions')}}</h3>

                <div class="heading-elements pull-right">

                   @if($subscriptions->count() >= 1)
                        <a class="btn btn-primary  dropdown-toggle  bulk-action-dropdown resp-mrg-btm-xs" href="#" id="dropdownMenuOutlineButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display:none;">
                            <i class="fa fa-plus"></i> {{tr('bulk_action')}}
                        </a>
                   @endif

                    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary"><i class="ft-plus icon-left"></i>{{ tr('add_subscription') }}</a>

                    <div class="dropdown-menu float-right" aria-labelledby="dropdownMenuOutlineButton2">

                        <a class="dropdown-item action_list" href="#" id="bulk_delete">
                            {{tr('delete')}}
                        </a>

                        <a class="dropdown-item action_list" href="#" id="bulk_approve">
                            {{ tr('approve') }}
                        </a>

                        <a class="dropdown-item action_list" href="#" id="bulk_decline">
                            {{ tr('decline') }}
                        </a>
                    </div>

                    <div class="bulk_action">

                        <form action="{{route('admin.subscriptions.bulk_action')}}" id="subscriptions_form" method="POST" role="search">

                            @csrf

                            <input type="hidden" name="action_name" id="action" value="">

                            <input type="hidden" name="selected_subscriptions" id="selected_ids" value="">

                            <input type="hidden" name="page_id" id="page_id" value="{{ (request()->page) ? request()->page : '1' }}">

                        </form>

                    </div>

                </div>

            </div>

            <!-- /.box-header -->
            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <!-- <li>To upload the project - Project owner needs to purchase the subscription plan. Project owners can’t add projects without a subscription. (By this we can stop unwanted projects to be uploaded and you can make revenue also)</li> -->
                            <li>Here you can set the subscription plan. </li>
                        </ul>
                    </p>
                </div>
                @include('admin.subscriptions._search')

                <div class="table-responsive">
                    
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">

                        <thead>

                            <tr>
                                <!-- <th>
                                    <input id="check_all" type="checkbox">
                                </th> -->
                                <th>{{tr('s_no')}}</th>
                                <th>{{tr('subscription_title')}}</th>
                                <th>{{tr('status')}}</th>
                                <th>{{tr('action')}}</th>
                            
                            </tr>

                        </thead>

                        <tbody>
                            @foreach($subscriptions as $i => $subscription)

                            <tr>

                                <!-- <td><input type="checkbox" name="row_check" class="faChkRnd" id="{{$subscription->id}}" value="{{$subscription->id}}"></td> -->

                                <td>{{$i+$subscriptions->firstItem()}}</td>

                                <td>
                                    <a href="{{route('admin.subscriptions.view' , ['subscription_id' => $subscription->id])}}"> {{ $subscription->title ?: tr('not_available')}}
                                    </a>
                                </td>

                                <td>

                                    @if($subscription->status == APPROVED)

                                        <span class="badge badge-success">{{ tr('approved') }} </span>

                                    @else

                                        <span class="badge badge-danger">{{ tr('declined') }} </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="btn-group" role="group">

                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> {{ tr('action') }}</button>

                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                            <a class="dropdown-item" href="{{ route('admin.subscriptions.view', ['subscription_id' => $subscription->id] ) }}">&nbsp;{{ tr('view') }}</a>


                                            @if(Setting::get('is_demo_control_enabled') == YES)

                                            <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('edit') }}</a>

                                            <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('delete') }}</a>

                                            @else

                                            <a class="dropdown-item" href="{{ route('admin.subscriptions.edit', ['subscription_id' => $subscription->id] ) }}">&nbsp;{{ tr('edit') }}</a>

                                            <a class="dropdown-item" onclick="return confirm(&quot;{{ tr('subscription_delete_confirmation' , $subscription->title ?: tr('not_available')) }}&quot;);" href="{{ route('admin.subscriptions.delete', ['subscription_id' => $subscription->id,'page'=>request()->input('page')] ) }}">&nbsp;{{ tr('delete') }}</a>

                                            @endif

                                            @if($subscription->status == APPROVED)

                                            <a class="dropdown-item" href="{{  route('admin.subscriptions.status' , ['subscription_id' => $subscription->id] )  }}" onclick="return confirm(&quot;{{ $subscription->title ?: tr('not_available')}} - {{ tr('subscription_decline_confirmation') }}&quot;);">&nbsp;{{ tr('decline') }}
                                            </a>

                                            @else

                                            <a class="dropdown-item" href="{{ route('admin.subscriptions.status' , ['subscription_id' => $subscription->id] ) }}">&nbsp;{{ tr('approve') }}</a>

                                            @endif

                                             <a class="dropdown-item" href="{{ route('admin.subscription_payments.index' ,['subscription_id' => $subscription->id])}}">&nbsp;{{ tr('subscribers') }}</a>

                                        </div>

                                    </div>

                                </td>   

                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    <div class="pull-right">{{$subscriptions->appends(request()->input())->links("pagination::bootstrap-4")}}</div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@endsection

@section('scripts')

@if(Session::has('bulk_action'))
<script type="text/javascript">
    $(document).ready(function() {
        localStorage.clear();
    });
</script>
@endif

<script type="text/javascript">
    $(document).ready(function() {
        get_values();

        // Call to Action for Delete || Approve || Decline
        $('.action_list').click(function() {
            var selected_action = $(this).attr('id');
            if (selected_action != undefined) {
                $('#action').val(selected_action);
                if ($("#selected_ids").val() != "") {
                    if (selected_action == 'bulk_delete') {
                        var message = "{{ tr('admin_subscriptions_delete_confirmation') }}";
                    } else if (selected_action == 'bulk_approve') {
                        var message = "{{ tr('admin_subscriptions_approve_confirmation') }}";
                    } else if (selected_action == 'bulk_decline') {
                        var message = "{{ tr('admin_subscriptions_decline_confirmation') }}";
                    }
                    var confirm_action = confirm(message);

                    if (confirm_action == true) {
                        $("#subscriptions_form").submit();
                    }
                    // 
                } else {
                    alert('Please select the check box');
                }
            }
        });
        // single check
        var page = $('#page_id').val();
        $(':checkbox[name=row_check]').on('change', function() {
            var checked_ids = $(':checkbox[name=row_check]:checked').map(function() {
                    return this.id;
                })
                .get();

            localStorage.setItem("subscription_checked_items" + page, JSON.stringify(checked_ids));

            get_values();

        });
        // select all checkbox
        $("#check_all").on("click", function() {
            if ($("input:checkbox").prop("checked")) {
                $("input:checkbox[name='row_check']").prop("checked", true);
                var checked_ids = $(':checkbox[name=row_check]:checked').map(function() {
                        return this.id;
                    })
                    .get();
                // var page = {!! $subscriptions->lastPage() !!};
                console.log("subscription_checked_items" + page);

                localStorage.setItem("subscription_checked_items" + page, JSON.stringify(checked_ids));
                get_values();
            } else {
                $("input:checkbox[name='row_check']").prop("checked", false);
                localStorage.removeItem("subscription_checked_items" + page);
                get_values();
            }

        });

        // Get Id values for selected subscription
        function get_values() {
            var pageKeys = Object.keys(localStorage).filter(key => key.indexOf('subscription_checked_items') === 0);
            var values = Array.prototype.concat.apply([], pageKeys.map(key => JSON.parse(localStorage[key])));

            if (values) {
                $('#selected_ids').val(values);
            }

            for (var i = 0; i < values.length; i++) {
                $('#' + values[i]).prop("checked", true);
            }
        }

    });
</script>


<script>
    $('#subscriptions').addClass("active pcoded-trigger");
    $('#subscriptions-view').addClass("active");

    $(document).ready(function(){
       
       setTimeout(function(){
          $('#order-table_length').hide();
          $('#order-table_filter').hide();
          $('#order-table_paginate').hide();

       },300);
   });
   
</script>
@endsection