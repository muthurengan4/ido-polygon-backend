@extends('layouts.admin')

@section('title', tr('invested_projects'))

@section('content-header', tr('invested_projects'))

@section('breadcrumb')

    <li class="breadcrumb-item active">{{tr('invested_projects')}}</li>

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="box">

            <div class="box-header with-border">
                
                <h3 class="box-title">{{$title ?? tr('invested_projects')}}</h3>

            </div>

            <div class="box-body">

                <div class="callout bg-pale-secondary">
                    <h4>Notes:</h4>
                    <p>
                        <ul>
                            <li>All the staking transaction details will be displayed here. </li>
                        </ul>
                    </p>
                </div>

                @include('admin.invested_projects._search')

                <div class="table-responsive">

                    <table id="example2" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        
                        <thead>
                            
                            <tr>
                                <th>{{ tr('s_no') }}</th>
                                <th>{{ tr('project_name') }}</th>
                                <th>{{ tr('username') }}</th>
                                <th>{{tr('wallet_address')}}</th>
                                <th>{{ tr('staked') }}</th>
                                <th>{{ tr('unstaked') }}</th>
                            </tr>

                        </thead>
                        
                        <tbody>
                        
                            @foreach($invested_projects as $i => $invested_project)

                                <tr>
                                    
                                    <td>{{ $i+$invested_projects->firstItem() }}</td>

                                    <td class="white-space-nowrap">
                                        <a href="{{route('admin.projects.view' , ['project_id' => $invested_project->project_id])}}" class="custom-a">
                                            {{$invested_project->project->name ?? tr('not_available')}}
                                        </a>
                                        
                                    </td>

                                    <td class="white-space-nowrap">
                                        <a href="{{route('admin.users.view' , ['user_id' => $invested_project->user_id])}}" class="custom-a">
                                            {{$invested_project->user->name ?? tr('not_available')}}
                                        </a>
                                        
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.invested_projects.view', ['invested_project_id' => $invested_project->id] ) }}">
                                            <span class="text-info">{{$invested_project->wallet_address ?? tr('not_available')}}</span>
                                        </a>

                                    </td>

                                    <td>
                                        <span class="text-success">+ {{$invested_project->stacked_formatted}}</span>
                                    </td>
                                    
                                    <td>
                                        <span class="text-danger">- {{$invested_project->unstacked_formatted}}</span>
                                    </td>

                                </tr>
                                
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>

            <div class="box-footer clearfix">
                    
                <div class="pull-right rd-flex">{{ $invested_projects->appends(request()->input())->links('pagination::bootstrap-4') }}</div>
            </div>

        </div>

    </div>
</div>


@endsection