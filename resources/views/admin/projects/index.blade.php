@extends('layouts.admin')

@section('title', tr('projects'))

@section('content-header', tr('projects'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.projects.index')}}">{{ tr('projects') }}</a>
    </li>

    <li class="breadcrumb-item">{{$title ?? tr('list_projects')}}</li>

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="box">

            <div class="box-header with-border">
                
                <h3 class="box-title">{{$title ?? tr('list_projects')}}</h3>

                <div class="heading-elements pull-right">

                   <!-- @if($projects->count() >= 1)
                        <a class="btn btn-primary  dropdown-toggle  bulk-action-dropdown resp-mrg-btm-xs" href="#" id="dropdownMenuOutlineButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-plus"></i> {{tr('bulk_action')}}
                        </a>
                   @endif -->

                    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary"><i class="ft-plus icon-left"></i>{{ tr('add_project') }}</a>

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

            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <li>All the projects added by the project owner will be displayed here with basic information. </li>
                        </ul>
                    </p>
                </div>

                @include('admin.projects._search')

                <div class="table-responsive">
                    
                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        
                        <thead>
                            
                            <tr>
                                @if($projects->count() >= 1)
                                    <!-- <th>
                                        <input id="check_all" type="checkbox" class="chk-box-left">
                                    </th> -->
                                @endif
                                <th>{{ tr('s_no') }}</th>
                                <th colspan="2" rowspan="1">{{ tr('project_name') }}</th>
                                <th>{{ tr('project_owner') }}</th>
                                <th>{{ tr('tokens') }}</th>
                                <th>{{ tr('start_end_time') }}</th>
                                <th>{{ tr('status') }}</th>
                                <th>{{ tr('publish_status') }}</th>
                                <th>{{ tr('action') }}</th>
                            </tr>

                        </thead>
                        <tbody>
                            
                            @foreach($projects as $i => $project)

                                <tr>
                                    
                                    <!-- <td id="check{{$project->id}}"><input type="checkbox" name="row_check" class="faChkRnd chk-box-inner-left" id="{{$project->id}}" value="{{$project->id}}"></td> -->

                                    <td>{{ $i+$projects->firstItem() }}</td>

                                    <td><span><a href="#"><img class="project-round-img rounded-circle" src="{{$project->picture}}"></a></span></td>

                                    <td>
                                        <small><a class="text-yellow hover-warning" href="{{route('admin.projects_view_for_web' , ['project_id' => $project->id])}}">{{$project->name ?? tr('not_available')}}</a></small>

                                        <h6 class="text-muted">{{$project->token_symbol ?: tr('not_available')}}</h6>

                                    </td>

                                    <td class="white-space-nowrap">
                                        <a href="{{route('admin.users.view' , ['user_id' => $project->user_id])}}" class="custom-a">
                                            {{$project->username ?? tr('not_available')}}
                                        </a>
                                        
                                    </td>

                                    <td>
                                        <small><p class="text-yellow hover-warning">Total: {{ $project->total_tokens_formatted }}</p></small>

                                        <p class="text-muted">{{ $project->allowed_tokens_formatted}} Allowed</p>
                                    </td>

                                    <td>{{ common_date($project->start_time, Auth::guard('admin')->user()->timezone) }} - {{ common_date($project->end_time, Auth::guard('admin')->user()->timezone) }}</td>


                                    <td>
                                        @if($project->status == APPROVED)

                                            <span class="label label-success">{{ tr('approved') }}</span>

                                        @else

                                            <span class="label label-warning">{{ tr('declined') }}</span>

                                        @endif
                                    </td>

                                    <td><span class="label label-default"> {{$project->publish_status_formatted}}</span></td>

                                   
                                    <td>

                                        <div class="btn-group" role="group">

                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-menu-right" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ft-settings icon-left"></i> {{ tr('action') }}</button>

                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                                <a class="dropdown-item" href="{{route('admin.projects_view_for_web' , ['project_id' => $project->id])}}">&nbsp;{{ tr('view') }}</a>


                                                @if(Setting::get('is_demo_control_enabled') == YES)

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('edit') }}</a>

                                                <a class="dropdown-item" href="javascript:void(0)">&nbsp;{{ tr('delete') }}</a>

                                                @else

                                                @if($project->publish_status != PROJECT_PUBLISH_STATUS_CLOSED)
                                                <a class="dropdown-item" href="{{ route('admin.projects.edit', ['project_id' => $project->id] ) }}">&nbsp;{{ tr('edit') }}</a>
                                                @endif
                                                <a class="dropdown-item" onclick="return confirm(&quot;{{ tr('project_delete_confirmation' , $project->name) }}&quot;);" href="{{ route('admin.projects.delete', ['project_id' => $project->id,'page'=>request()->input('page')] ) }}">&nbsp;{{ tr('delete') }}</a>

                                                @endif

                                                @if($project->status == APPROVED)

                                                <a class="dropdown-item" href="{{  route('admin.projects.status' , ['project_id' => $project->id] )  }}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('project_decline_confirmation') }}&quot;);">&nbsp;{{ tr('decline') }}
                                                </a>

                                                @else

                                                <a class="dropdown-item" href="{{ route('admin.projects.status' , ['project_id' => $project->id] ) }}">&nbsp;{{ tr('approve') }}</a>

                                                @endif

                                                @if(in_array($project->publish_status, [PROJECT_PUBLISH_STATUS_INITIATED, PROJECT_PUBLISH_STATUS_SCHEDULED]) && $project->status == APPROVED)

                                                <a class="dropdown-item" href="{{  route('admin.projects.publish_status' , ['project_id' => $project->id, 'publish_status' => PROJECT_PUBLISH_STATUS_OPENED] )  }}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('are_you_sure') }}&quot;);">&nbsp;{{ tr('mark_as_opened') }}
                                                </a>

                                                @endif

                                                @if(in_array($project->publish_status, [PROJECT_PUBLISH_STATUS_OPENED]))

                                                <a class="dropdown-item" href="{{  route('admin.projects.publish_status' , ['project_id' => $project->id, 'publish_status' => PROJECT_PUBLISH_STATUS_CLOSED] )  }}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('are_you_sure') }}&quot;);">&nbsp;{{ tr('mark_as_closed') }}
                                                </a>

                                                @endif

                                                <a class="dropdown-item" href="{{ route('admin.invested_projects' , ['project_id' => $project->id] ) }}">&nbsp;{{ tr('invested_users') }}</a>


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
                    
                <div class="pull-right rd-flex">{{ $projects->appends(request()->input())->links('pagination::bootstrap-4') }}</div>
            </div>

        </div>

    </div>
</div>


@endsection