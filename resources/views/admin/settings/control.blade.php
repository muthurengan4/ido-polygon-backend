@extends('layouts.admin') 

@section('title', tr('settings'))

@section('content-header', tr('settings'))

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">{{ tr('settings') }}</li>

@endsection 


@section('content')

<div class="col-md-12 col-12">
    
    <div class="box box-default">
        
        <div class="box-header with-border">
            <h3 class="box-title">{{ tr('settings') }}</h3>
        </div>
        
        <div class="box-body">

            <div class="box box-solid bg-black">
                <div class="box-header with-border">
                    <h3 class="box-title">{{tr('site_settings')}}</h3>
                </div>
                <form id="site_settings_save" action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data" role="form">

                    @csrf
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                             <div class="form-group col-md-6">

                                <label for="backend_url">{{tr('backend_url')}} *</label>

                                <input type="text" class="form-control" id="backend_url" name="backend_url" placeholder="{{tr('backend_url')}}" value="{{Setting::get('backend_url')}}">

                            </div>

                            <div class="form-group col-md-6">

                                <label for="frontend_url">{{tr('frontend_url')}} *</label>

                                <input type="text" class="form-control" id="frontend_url" name="frontend_url" placeholder="{{tr('frontend_url')}}" value="{{Setting::get('frontend_url')}}">

                            </div>
                            <div class="form-group col-md-6">
                                <label for="is_account_email_verification">{{tr('is_account_email_verification')}} *</label>
                                <input type="text" class="form-control" id="is_account_email_verification" name="is_account_email_verification" placeholder="Enter {{tr('is_account_email_verification')}}" value="{{Setting::get('is_account_email_verification')}}">
                            </div> 

                            <div class="form-group col-md-6">
                                <label for="admin_email_address">{{tr('admin_email_address')}} *</label>
                                <input type="text" class="form-control" id="admin_email_address" name="admin_email_address" placeholder="Enter {{tr('admin_email_address')}}" value="{{Setting::get('admin_email_address')}}">
                            </div> 

                            <div class="form-group col-md-6">
                                <label for="is_email_notification">{{tr('is_email_notification')}} *</label>
                                <input type="text" class="form-control" id="is_email_notification" name="is_email_notification" placeholder="Enter {{tr('is_email_notification')}}" value="{{Setting::get('is_email_notification')}}">
                            </div> 

                            <div class="form-group col-md-6">
                                <label for="crypto_url">{{tr('crypto_url')}} *</label>
                                <input type="text" class="form-control" id="crypto_url" name="crypto_url" placeholder="Enter {{tr('crypto_url')}}" value="{{Setting::get('crypto_url')}}">
                            </div>                            
                        </div>
                        
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">

                        <div class="pull-right">
                        
                            <button type="reset" class="btn btn-warning mr-1">
                                <i class="ft-x"></i> {{ tr('reset') }} 
                            </button>

                            <button type="submit" class="btn btn-primary" @if(Setting::get('is_demo_control_enabled') == YES) disabled @endif ><i class="fa fa-check-square-o"></i>{{ tr('submit') }}</button>
                        
                        </div>

                        <div class="clearfix"></div>

                    </div>

                </form>
            
            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script type="text/javascript">
    
    $(document).ready(function() {
        $("div.fansclub-tab-menu>div.list-group>a").click(function(e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.fansclub-tab>div.fansclub-tab-content").removeClass("active");
            $("div.fansclub-tab>div.fansclub-tab-content").eq(index).addClass("active");
        });
    });
</script>
@endsection