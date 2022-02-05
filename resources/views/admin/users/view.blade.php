@extends('layouts.admin')

@section('title', tr('users'))

@section('content-header', tr('users'))

@section('breadcrumb')

    <li class="breadcrumb-item active">
        <a href="{{route('admin.users.index')}}">{{ tr('users') }}</a>
    </li>

    <li class="breadcrumb-item">{{tr('view_users')}}</li>

@endsection

@section('content')

<div class="box">
    
    <div class="box-body">

    	<div class="row">
			
			<div class="col-md-12">
                
		    	<div class="media-list media-list-divided">

					<div class="media media-single">

					  	<img class="w-80" src="{{$user->picture}}" alt="...">
					  	<div class="media-body">
							<h6>{{$user->name}}</h6>
							<small class="text-fader">{{common_date($user->created_at , Auth::guard('admin')->user()->timezone)}}</small>
					  	</div>

					  	@if($user->status == APPROVED)

					  		<div class="media-right">

                            	<a class="btn bg-navy margin" href="{{  route('admin.users.status' , ['user_id' => $user->id] )  }}" onclick="return confirm(&quot;{{ $user->name }} - {{ tr('user_decline_confirmation') }}&quot;);"> <i class="fa fa-check"></i>  {{ tr('decline') }}
                                                </a>
                            </div>

                        @else

                        	<div class="media-right">

                            	<a class="btn bg-navy margin" href="{{ route('admin.users.status' , ['user_id' => $user->id] ) }}"> <i class="fa fa-check"></i>  {{ tr('approve') }}</a>
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

	                        	<a class="btn bg-purple margin" href="{{ route('admin.users.edit', ['user_id' => $user->id] ) }}"><i class="fa fa-edit"></i> {{tr('edit')}}</a>
	                        </div>	

	                        <div class="media-right">

	                        	<a class="btn bg-olive margin" onclick="return confirm(&quot;{{ tr('user_delete_confirmation' , $user->name) }}&quot;);" href="{{ route('admin.users.delete', ['user_id' => $user->id] ) }}"><i class="fa fa-trash"></i> {{tr('delete')}}</a>
	                        </div>

                        @endif

 					</div>
				</div>
			</div>
		</div>
    </div>
</div>      

<div class="row">
	
	<div class="col-md-12">
		
		<div class="box">
			
			<div class="box-body">

                <div class="row">

                    <div class="col-md-6">

                        <div class="table-responsive">
        					<table class="table table-striped table-hover no-margin">
                                <tbody>
                                 	<tr>
                                    	<th>{{tr('name')}}</th>
                                    	<td>{{$user->name}}</td>
                                  	</tr>
                                  	<tr>
                                    	<th>{{tr('wallet_address')}}</th>
                                    	<td>{{$user->wallet_address}}</td>
                                  	</tr>
                                  	<tr>
                                        <th>{{tr('timezone')}}</th>
                                        <td class="text-capitalize">{{$user->timezone ?: tr('not_available')}}</td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="table-responsive">
                            <table class="table table-striped table-hover no-margin">
                                <tbody>
                                    <!-- <tr>
                                        <td>{{tr('document_status')}}</td>
                                        <td>
                                            @if($user->is_document_verified == USER_DOCUMENT_APPROVED)

                                                <span class="label label-success">{{ tr('approved') }}</span>

                                            @elseif($user->is_document_verified == USER_DOCUMENT_DECLINED)

                                                <span class="label label-danger">{{ tr('declined') }}</span>

                                            @else

                                                <span class="label label-warning">{{ tr('pending') }}</span>

                                            @endif
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td>{{tr('status')}}</td>
                                        <td>
                                            @if($user->status == APPROVED)

                                                <span class="btn btn-success btn-sm">{{ tr('approved') }}</span>

                                            @else

                                                <span class="btn btn-warning btn-sm">{{ tr('declined') }}</span>

                                            @endif
                                        </td>
                                    </tr>

                                    <!-- <tr>
                                        <td>{{tr('emai_verification')}}</td>
                                        <td>
                                            @if($user->is_email_verified == USER_EMAIL_NOT_VERIFIED)

                                                <a class="label label-warning" href="{{ route('admin.users.verify', ['user_id' => $user->id]) }}">{{ tr('verify') }}</a>

                                            @else

                                            <span class="label label-info">{{ tr('verified') }}</span>

                                            @endif
                                        </td>
                                    </tr> -->



                                    <tr>
                                        <td>{{tr('created_at')}}</td>
                                        <td>{{common_date($user->created_at, Auth::guard('admin')->user()->timezone)}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{tr('updated_at')}}</td>
                                        <td>{{common_date($user->updated_at, Auth::guard('admin')->user()->timezone)}}</td>
                                    </tr>

                               </tbody>
                            </table>
                        
                        </div>

                        <div class="row">
                            
                            <div class="media-list media-list-divided">

                                <div class="media media-single">
                                    <div class="media-right">

                                        <a class="btn bg-warning margin" href="{{ route('admin.projects.index', ['user_id' => $user->id] ) }}"><i class="fa fa-eye"></i> {{tr('projects')}}</a>
                                    
                                    </div>

                                    <!-- <div class="media-right">

                                        <a class="btn bg-success margin" href="{{ route('admin.subscription_payments.index', ['user_id' => $user->id] ) }}"><i class="fa fa-eye"></i> {{tr('subscriptions')}}</a>
                                    
                                    </div> --> 

                                    <div class="media-right">

                                        <a class="btn bg-info margin" href="{{ route('admin.invested_projects', ['user_id' => $user->id] ) }}"><i class="fa fa-eye"></i> {{tr('invested_projects')}}</a>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
				</div>
			</div>
			<!-- /.box-body -->
		 </div>
	</div>
</div>

@endsection
