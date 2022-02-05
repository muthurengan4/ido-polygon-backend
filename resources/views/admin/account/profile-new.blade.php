@extends('layouts.admin') 

@section('title', tr('profile')) 

@section('content-header', tr('profile')) 

@section('breadcrumb')

<li class="breadcrumb-item active">{{tr('profile')}}</li>

@endsection 

@section('content')

<div class="row">
    <div class="col-xl-4 col-lg-5">
        <!-- Profile Image -->
        <div class="box">
            <div class="box-body box-profile">
                
                <img class="profile-user-img rounded-circle img-fluid mx-auto d-block" src="{{Auth::guard('admin')->user()->picture}}" alt="{{Auth::guard('admin')->user()->name}}">
                
                <h3 class="profile-username text-center">{{Auth::guard('admin')->user()->name}}</h3>
                
                <p class="text-muted text-center">{{Auth::guard('admin')->user()->email}}</p>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-xl-8 col-lg-7">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                
                <li><a class="active" href="#update_profile" data-toggle="tab">{{tr('update_profile')}}</a></li>
                
                <li><a id="base-tab_upload_image" data-toggle="tab" aria-controls="tab_upload_image" href="#tab_upload_image" aria-expanded="false">{{tr('upload_image')}}</a></li>
                
                <li><a href="#tab_change_password" id="base-tab_change_password"  data-toggle="tab" aria-controls="tab_change_password" href="#tab_change_password" aria-expanded="false">{{tr('change_password')}}</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="update_profile">
                    <form class="form-horizontal" action="{{ (Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.profile.save') }}" method="POST" enctype="multipart/form-data" role="form">
                        @csrf

                        <input type="hidden" name="admin_id" value="{{Auth::guard('admin')->user()->id}}">

                        <div class="form-group">
                            <label for="name" required class="col-sm-2 control-label">{{ tr('name') }}</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::guard('admin')->user()->name }}" placeholder="{{ tr('username') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">{{ tr('email') }}</label>

                            <div class="col-sm-10">
                                <input type="email" required value="{{ Auth::guard('admin')->user()->email }}" name="email" class="form-control" id="email" placeholder="{{ tr('email') }}">
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label for="about" class="col-sm-2 control-label">{{ tr('about') }}</label>

                            <div class="col-sm-10">
                                <input type="text" value="{{ Auth::guard('admin')->user()->about }}" name="about" class="form-control" id=" " placeholder="{{ tr('about') }}">
                               
                            </div>
                        </div> -->

                        <div class="form-actions padding-btm-zero">

                            <button type="reset" class="btn btn-warning mr-1">
                                <i class="ft-x mr-1"></i> {{ tr('reset') }} 
                            </button>

                            <button type="submit" class="btn btn-primary" @if(Setting::get('is_demo_control_enabled') == YES) disabled @endif ><i class="fa fa-check-square-o mr-1"></i>{{ tr('submit') }}</button>
                            
                            <div class="clearfix"></div>

                        </div>

                    </form>
                </div>

                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_upload_image" aria-labelledby="base-tab_upload_image">
                    <form class="form-horizontal" action="{{ (Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.profile.save') }}" method="POST" enctype="multipart/form-data" role="form">
                        @csrf

                         <input type="hidden" name="admin_id" value="{{Auth::guard('admin')->user()->id}}">

                        @if(Auth::guard('admin')->user()->picture)
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{ Auth::guard('admin')->user()->picture }}"> 
                        @else
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" class="profile-user-img img-responsive img-circle" src="{{ asset('placeholder.jpeg') }}">
                         @endif

                        <div class="form-group">
                            <label for="picture" class="col-sm-2 control-label">{{ tr('picture') }}</label>

                            <div class="col-sm-10">
                                <input type="file" required accept="image/png,image/jpeg" name="picture" id="picture">
                                <p class="help-block">{{ tr('image_validate') }}</p>
                            </div>
                        </div>

                        <div class="form-actions">

                            <button type="reset" class="btn btn-warning mr-1">
                                <i class="ft-x"></i> {{ tr('reset') }} 
                            </button>

                            <button type="submit" class="btn btn-primary" @if(Setting::get('is_demo_control_enabled') == YES) disabled @endif ><i class="fa fa-check-square-o"></i>{{ tr('submit') }}</button>
                            
                            <div class="clearfix"></div>

                        </div>

                    </form>
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="tab_change_password" aria-labelledby="base-tab_change_password">

                    <form class="form-horizontal" action="{{ (Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.change.password') }}" method="POST" enctype="multipart/form-data" role="form">
                        @csrf

                        <input type="hidden" name="admin_id" value="{{ Auth::guard('admin')->user()->id }}">

                        <div class="form-group">
                            <label for="old_password" class="col-sm-3 control-label">{{tr('old_password')}} *</label>

                            <div class="col-sm-8">
                                <input required type="password" class="form-control" name="old_password" id="old_password" placeholder="{{ tr('old_password') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-sm-3 control-label">{{tr('new_password')}} *</label>

                            <div class="col-sm-8">
                                <input required type="password" class="form-control" name="password" id="password" placeholder="{{ tr('new_password') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="col-sm-3 control-label">{{tr('confirm_password')}} *</label>

                            <div class="col-sm-8">
                                <input type="password" required class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Enter {{tr('confirm_password')}}">
                            </div>
                        </div>

                        <div class="form-actions">

                            <button type="reset" class="btn btn-warning mr-1">
                                <i class="ft-x"></i> {{ tr('reset') }} 
                            </button>

                            <button type="submit" class="btn btn-primary" @if(Setting::get('is_demo_control_enabled') == YES) disabled @endif ><i class="fa fa-check-square-o"></i>{{ tr('submit') }}</button>
                            
                            <div class="clearfix"></div>

                        </div>

                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->

</div>

@endsection