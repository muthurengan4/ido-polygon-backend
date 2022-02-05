@extends('layouts.admin')

@section('title', tr('users'))

@section('content-header', tr('users'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.users.index')}}">{{ tr('users') }}</a>
    </li>

    <li class="breadcrumb-item">{{$title ?? tr('view_users')}}</li>

@endsection

@section('content')

<div class="row">   

    <div class="col-md-12">
         
        <div class="box">

            <div class="box-header with-border">
                <h3 class="box-title">{{$title ?? tr('view_users')}}</h3>

                <div class="heading-elements pull-right">

                   @if($users->count() >= 1)
                        <a class="btn btn-primary  dropdown-toggle  bulk-action-dropdown resp-mrg-btm-xs" href="#" id="dropdownMenuOutlineButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-plus"></i> {{tr('bulk_action')}}
                        </a>
                   @endif


                    <a href="{{ route('admin.users.excel',['downloadexcel'=>'excel','status'=>Request::get('status'),'search_key'=>Request::get('search_key')]) }}" class="btn btn-primary resp-mrg-btm-xs">Export to Excel</a>

                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="ft-plus icon-left"></i>{{ tr('add_user') }}</a>

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

                        <form action="{{route('admin.users.bulk_action')}}" id="users_form" method="POST" role="search">

                            @csrf

                            <input type="hidden" name="action_name" id="action" value="">

                            <input type="hidden" name="selected_users" id="selected_ids" value="">

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
                            <li>User can be both Project owner(The person who add the token for presale or IDO) and Investor(The person who invest the project token or The tokens added by the Project owner)</li>
                            <li>User A can be a project owner as well as investor. </li>
                            <li>User A invest in any of the project token - Then User A will be investor. </li>
                            <li>User A add any project into launch page - Then user A will be Project owner. </li>
                        </ul>
                    </p>
                </div>

                @include('admin.users._search')

                <div class="table-responsive">
                    
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">

                        <thead>
                            <tr>
                                @if($users->count() >= 1)
                                    <th>
                                        <input id="check_all" type="checkbox" class="chk-box-left">
                                    </th>
                                @endif

                                <th>{{tr('s_no')}}</th>
                                <th>{{tr('name')}}</th>
                                <th>{{tr('wallet_address')}}</th>
                                <!-- <th>{{tr('document_status')}}</th> -->
                                <th>{{tr('status')}}</th>
                                <!-- <th>{{tr('verify')}}</th> -->
                                <th>{{tr('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($users as $i => $user)

                                <tr>

                                    <td id="check{{$user->id}}">
                                        <input type="checkbox" name="row_check" class="faChkRnd chk-box-inner-left" id="{{$user->id}}" value="{{$user->id}}">
                                    </td>

                                    <td>{{ $i+$users->firstItem() }}</td>

                                    <td class="white-space-nowrap">
                                        <a href="{{route('admin.users.view' , ['user_id' => $user->id])}}" class="custom-a">
                                            {{$user->name ?: tr('not_available')}}
                                        </a>
                                        
                                    </td>

                                    <td>
                                        {{ $user->wallet_address ?: tr('not_available') }}
                                    </td>

                                    <!-- <td>
                                        @if($user->is_document_verified == USER_DOCUMENT_APPROVED)

                                            <span class="label label-success">{{ tr('approved') }}</span>

                                        @elseif($user->is_document_verified == USER_DOCUMENT_DECLINED)

                                            <span class="label label-danger">{{ tr('declined') }}</span>

                                        @else

                                            <span class="label label-warning">{{ tr('pending') }}</span>

                                        @endif
                                    </td> -->

                                    <td>
                                        @if($user->status == USER_APPROVED)

                                            <span class="label label-success">{{ tr('approved') }}</span>

                                        @else

                                            <span class="label label-warning">{{ tr('declined') }}</span>

                                        @endif
                                    </td>

                                    <!-- <td>
                                        @if($user->is_email_verified == USER_EMAIL_NOT_VERIFIED)

                                        <a class="label label-warning" href="{{ route('admin.users.verify', ['user_id' => $user->id]) }}">
                                            {{ tr('verify') }}
                                        </a>

                                        @else

                                        <span class="label label-info">{{ tr('verified') }}</span>

                                        @endif
                                    </td> -->

                                   
                                    <td>

                                        <div class="btn-group" role="group">

                                            <button class="btn btn-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> {{ tr('action') }}</button>

                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                                <a class="dropdown-item" href="{{ route('admin.users.view', ['user_id' => $user->id] ) }}">&nbsp;{{ tr('view') }}</a>


                                                @if(Setting::get('is_demo_control_enabled') == YES)

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('edit') }}</a>

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('delete') }}</a>

                                                @else

                                                <a class="dropdown-item" href="{{ route('admin.users.edit', ['user_id' => $user->id] ) }}">&nbsp;{{ tr('edit') }}</a>

                                                <a class="dropdown-item" onclick="return confirm(&quot;{{ tr('user_delete_confirmation' , $user->name) }}&quot;);" href="{{ route('admin.users.delete', ['user_id' => $user->id,'page'=>request()->input('page')] ) }}">&nbsp;{{ tr('delete') }}</a>

                                                @endif

                                                @if($user->status == APPROVED)

                                                <a class="dropdown-item" href="{{  route('admin.users.status' , ['user_id' => $user->id] )  }}" onclick="return confirm(&quot;{{ $user->name }} - {{ tr('user_decline_confirmation') }}&quot;);">&nbsp;{{ tr('decline') }}
                                                </a>

                                                @else

                                                <a class="dropdown-item" href="{{ route('admin.users.status' , ['user_id' => $user->id] ) }}">&nbsp;{{ tr('approve') }}</a>

                                                @endif

                                                <a class="dropdown-item" href="{{ route('admin.projects.index' , ['user_id' => $user->id] ) }}">&nbsp;{{ tr('projects') }}</a>

                                                <a class="dropdown-item" href="{{ route('admin.invested_projects' , ['user_id' => $user->id] ) }}">&nbsp;{{ tr('invested_projects') }}</a>

                                                <!-- <a class="dropdown-item" href="{{ route('admin.subscription_payments.index' , ['user_id' => $user->id] ) }}">&nbsp;{{ tr('subscriptions') }}</a> -->

                                            </div>

                                        </div>

                                    </td>

                                </tr>
                                
                            @endforeach                         
                        </tbody>                       
                    </table>
                
                </div>

            </div>

            <div class="box-footer clearfix">
                    
                <div class="pull-right rd-flex">
                    {{$users->appends(request()->input())->links('pagination::bootstrap-4')}}
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
                        var message = "{{ tr('admin_users_delete_confirmation') }}";
                    } else if (selected_action == 'bulk_approve') {
                        var message = "{{ tr('admin_users_approve_confirmation') }}";
                    } else if (selected_action == 'bulk_decline') {
                        var message = "{{ tr('admin_users_decline_confirmation') }}";
                    }
                    var confirm_action = confirm(message);

                    if (confirm_action == true) {
                        $("#users_form").submit();
                    }
                    // 
                } else {
                    alert('Please select the check box');
                }
            }
        });
        // single check
        var page = $('#page_id').val();
        $('.faChkRnd:checkbox[name=row_check]').on('change', function() {

            var checked_ids = $(':checkbox[name=row_check]:checked').map(function() {
                return this.id;
            }).get();

            localStorage.setItem("user_checked_items" + page, JSON.stringify(checked_ids));

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
                // var page = {!! $users->lastPage() !!};
                console.log("user_checked_items" + page);

                localStorage.setItem("user_checked_items" + page, JSON.stringify(checked_ids));
                get_values();
            } else {
                $("input:checkbox[name='row_check']").prop("checked", false);
                localStorage.removeItem("user_checked_items" + page);
                get_values();
            }

        });

        // Get Id values for selected User
        function get_values() {
            var pageKeys = Object.keys(localStorage).filter(key => key.indexOf('user_checked_items') === 0);
            var values = Array.prototype.concat.apply([], pageKeys.map(key => JSON.parse(localStorage[key])));

            if (values) {
                $('#selected_ids').val(values);
            }

            for (var i = 0; i < values.length; i++) {
                $('#' + values[i]).prop("checked", true);
            }
        }



    });

  // to accept trailing zeroes
    $(document).ready(function(){
        // $('.non_zero').on('input change', function (e) {
        //     var reg = /^0+/gi;
        //     if (this.value.match(reg)) {
        //         this.value = this.value.replace(reg, '');
        //     }
        // });
     });

    $(document).ready(function (e) {

    $(".card-dashboard").scroll(function () {
        if($('.chk-box-inner-left').length <= 5){
            $(this).removeClass('table-responsive');
        }
    });

  });

</script>

@endsection