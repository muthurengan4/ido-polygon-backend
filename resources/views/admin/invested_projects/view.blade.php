@extends('layouts.admin')

@section('title', tr('invested_projects'))

@section('content-header', tr('invested_projects'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.invested_projects')}}">{{ tr('invested_projects') }}</a>
    </li>

    <li class="breadcrumb-item">{{tr('view_invested_projects')}}</li>

@endsection

@section('content')

<div class="row">
    
    <div class="col-lg-6 col-12">
        
        <div class="box">
            
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover no-margin">
                        <tbody>
                            <tr>
                                <td>{{tr('unique_id')}}</td>
                                <td>{{$invested_project->project->project_unique_id ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('project_name')}}</td>
                                <td>{{$invested_project->project->name ?? tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('username')}}</td>
                                <td>{{$invested_project->user->username ?? tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('token_symbol')}}</td>
                                <td>{{$invested_project->project->token_symbol ?? tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('total_tokens')}}</td>
                                <td>{{$invested_project->project->total_tokens_formatted ?? tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('allowed_tokens')}}</td>
                                <td>{{$invested_project->project->allowed_tokens_formatted ?? tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('exchange_rate')}}</td>
                                <td>{{$invested_project->project->exchange_rate ?? tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('access_type')}}</td>
                                <td>{{$invested_project->project->access_type ?? tr('not_available')}}</td>
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
                                <td>{{tr('purchased_username')}}</td>
                                <td>{{$invested_project->username ?? tr('not_available')}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('total_tokens_purchased')}}</td>
                                <td>{{$invested_project->purchased_formatted}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('start_time')}}</td>
                                <td>{{$invested_project->project->start_time?? ''}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('end_time')}}</td>
                                <td>{{$invested_project->project->end_time?? ''}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('created_at')}}</td>
                                <td>{{$invested_project->created_at}}</td>
                            </tr>
                            <tr>
                                <td>{{tr('updated_at')}}</td>
                                <td>{{$invested_project->updated_at}}</td>
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