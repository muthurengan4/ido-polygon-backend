@extends('layouts.admin')

@section('title', tr('projects'))

@section('content-header', tr('project'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.projects.index')}}">{{ tr('projects') }}</a>
    </li>

    <li class="breadcrumb-item">{{tr('view_project')}}</li>

@endsection

@section('content')

<div class="box">
    
    <div class="box-body">

        <div class="row">
            
            <div class="col-md-12">
                <div class="media-list media-list-divided">

                    <div class="media media-single">

                        <img class="w-80 border-2" src="{{$project->picture}}" alt="...">
                        
                        <div class="media-body">
                            <h6>{{$project->name}}</h6>
                            <small class="text-fader">{{common_date($project->created_at , Auth::guard('admin')->user()->timezone)}}</small>
                        </div>

                        @if($project->status == APPROVED)

                            <div class="media-right">

                                <a class="btn btn-warning margin" href="{{  route('admin.projects.status', ['project_id' => $project->id])}}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('project_decline_confirmation') }}&quot;);">
                                        <i class="fa fa-check"></i> {{ tr('decline') }}
                                </a>
                            </div>

                        @else

                            <div class="media-right">

                                <a class="btn bg-navy margin" href="{{ route('admin.projects.status' , ['project_id' => $project->id] ) }}"> <i class="fa fa-check"></i>  {{ tr('approve') }}</a>
                            </div>

                        @endif

                        @if(Setting::get('is_demo_control_enabled') == YES)

                            <div class="media-right">
                                <button class="btn bg-purple margin"><i class="fa fa-edit"></i> {{tr('edit')}}</button>
                            </div>

                            <div class="media-right">
                                <button class="btn bg-olive margin"><i class="fa fa-trash"></i> {{tr('delete')}}</button>
                            </div>

                        @else

                            <div class="media-right">

                                <a class="btn bg-purple margin" href="{{ route('admin.projects.edit', ['project_id' => $project->id] ) }}"><i class="fa fa-edit"></i> {{tr('edit')}}</a>
                            </div>  

                            <div class="media-right">

                                <a class="btn bg-olive margin" onclick="return confirm(&quot;{{ tr('project_delete_confirmation' , $project->name) }}&quot;);" href="{{ route('admin.projects.delete', ['project_id' => $project->id] ) }}"><i class="fa fa-trash"></i> {{tr('delete')}}</a>
                            </div>

                           

                        @endif

                        @if(in_array($project->publish_status, [PROJECT_PUBLISH_STATUS_INITIATED, PROJECT_PUBLISH_STATUS_SCHEDULED]) && $project->status == APPROVED)

                            <a class="btn btn-primary margin" href="{{  route('admin.projects.publish_status' , ['project_id' => $project->id, 'publish_status' => PROJECT_PUBLISH_STATUS_OPENED] )  }}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('are_you_sure') }}&quot;);"><i class="fa fa-folder-open"></i> {{ tr('mark_as_opened') }}
                            </a>

                        @endif

                        @if(in_array($project->publish_status, [PROJECT_PUBLISH_STATUS_OPENED]))

                            <a class="btn btn-warning margin" href="{{  route('admin.projects.publish_status' , ['project_id' => $project->id, 'publish_status' => PROJECT_PUBLISH_STATUS_CLOSED] )  }}" onclick="return confirm(&quot;{{ $project->name }} - {{ tr('are_you_sure') }}&quot;);"><i class="fa fa-folder"></i> {{ tr('mark_as_closed') }}
                            </a>

                        @endif

                        <a class="btn btn-success margin" href="{{ route('admin.invested_projects' , ['project_id' => $project->id] ) }}"><i class="fa fa-user"></i> {{ tr('invested_users') }}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-6 col-12">
        
        <div class="box">
            
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover no-margin">
                        <tbody>
                            <tr>
                                <td>{{tr('unique_id')}}</td>
                                <td>{{$project->project_unique_id}}</td>
                            </tr>

                            <tr>
                                <td>{{tr('project_owner_name')}}</td>
                                <td><a href="{{route('admin.users.view', ['user_id' => $project->user_id])}}"> {{$project->username ?: tr('not_available')}}</a></td>
                            </tr>
                            <tr>
                                <td>{{tr('token_symbol')}}</td>
                                <td>{{$project->token_symbol  ?: tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('total_tokens')}}</td>
                                <td>{{$project->total_tokens_formatted}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('allowed_tokens')}}</td>
                                <td>{{$project->allowed_tokens_formatted}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('exchange_rate')}}</td>
                                <td>{{$project->exchange_rate  ?: tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('access_type')}}</td>
                                <td>{{$project->access_type  ?: tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('start_time')}}</td>
                                <td>{{common_date($project->start_time, Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('end_time')}}</td>
                                <td>{{common_date($project->end_time, Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
         </div>
    </div>
    
    <div class="col-lg-6 col-12">
         
        <div class="box">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover no-margin">
                        <tbody>
                            <tr>
                                <td>{{tr('total_invisted_users')}}</td>
                                <td>
                                    <a class="btn btn-success btn-xs" href="{{ route('admin.invested_projects' , ['project_id' => $project->id] ) }}">
                                    {{$project->total_users_participated  ?: 0}}
                                     </a>
                                </td>
                            </tr>
                            <tr>
                                <td>{{tr('total_tokens_purchased')}}</td>
                                <td>{{$project->total_tokens_purchased_formatted}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('status')}}</td>
                                <td>
                                    @if($project->status == APPROVED)

                                        <span class="btn btn-success btn-sm">{{ tr('approved') }}</span>

                                    @else

                                        <span class="btn btn-warning btn-sm">{{ tr('declined') }}</span>

                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{tr('uploaded_by')}}</td>
                                <td class="text-uppercase">{{$project->uploaded_by  ?: tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('publish_status')}}</td>
                                <td>

                                    <span class="btn btn-primary btn-sm">{{$project->publish_status_formatted}}</span>

                                </td>
                            </tr>
                            <tr>
                                <td>{{tr('created_at')}}</td>
                                <td>{{common_date($project->created_at, Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('updated_at')}}</td>
                                <td>{{common_date($project->updated_at, Auth::guard('admin')->user()->timezone)}}</td>
                            </tr>
                       </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

@endsection
