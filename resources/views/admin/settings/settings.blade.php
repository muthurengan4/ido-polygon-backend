@extends('layouts.admin') 

@section('title', tr('settings'))

@section('content-header', tr('settings'))

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">{{ tr('settings') }}</li>

@endsection 
@section('styles')

<style>
    .vtabs .tab-content {
        padding: 0px 10px !important;
    }
</style>
@endsection 


@section('content')

<div class="col-md-12 col-12">
    
    <div class="box box-default">
        
        <div class="box-header with-border">
            <h3 class="box-title">{{ tr('settings') }}</h3>
        </div>
        
        <div class="box-body">

            <div class="callout bg-pale-secondary">
                <h4>Notes:</h4>
                <p>
                    <ul>
                        <li>You can manage the site logo, icon and sitename from the settings. </li>
                    </ul>
                </p>
            </div>

            <div class="vtabs">
                
                <ul class="nav nav-tabs tabs-vertical" role="tablist" style="width: 25%">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#site_settings" role="tab">
                            <span class="hidden-sm-up">
                                <i class="ion-home"></i>
                            </span>
                            <span class="hidden-xs-down">{{tr('site_settings')}}</span>
                        </a> 
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#configuration_settings" role="tab">
                            <span class="hidden-sm-up">
                                <i class="ion-person"></i>
                            </span>
                            <span class="hidden-xs-down">{{tr('configuration_settings')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#email_settings" role="tab">
                            <span class="hidden-sm-up">
                                <i class="ion-email"></i>
                            </span> 
                            <span class="hidden-xs-down">{{tr('email_settings')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#social_settings" role="tab">
                            <span class="hidden-sm-up">
                                <i class="ion-email"></i>
                            </span> 
                            <span class="hidden-xs-down">{{tr('social_settings')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contact_information" role="tab">
                            <span class="hidden-sm-up">
                                <i class="ion-email"></i>
                            </span> 
                            <span class="hidden-xs-down">{{tr('contact_information')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#blockchain_connection" role="tab">
                            <span class="hidden-sm-up">
                                <i class="ion-email"></i>
                            </span> 
                            <span class="hidden-xs-down">{{tr('blockchain')}}</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#other_settings" role="tab">
                            <span class="hidden-sm-up">
                                <i class="ion-email"></i>
                            </span> 
                            <span class="hidden-xs-down">{{tr('other_settings')}}</span>
                        </a>
                    </li> -->
                
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="site_settings" role="tabpanel">

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
                                            <label for="site_name">{{tr('site_name')}} *</label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" placeholder="Enter {{tr('site_name')}}" value="{{Setting::get('site_name')}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="tag_name">{{tr('tag_name')}} *</label>
                                            <input type="text" class="form-control" id="tag_name" name="tag_name" placeholder="Enter {{tr('tag_name')}}" value="{{Setting::get('tag_name')}}">
                                        </div>


                                        <div class="form-group col-md-6">

                                            <label for="site_icon">{{tr('site_icon')}} *</label>

                                            <p class="txt-warning">{{tr('png_image_note')}}</p>

                                            <input type="file" class="form-control" id="site_icon" name="site_icon" accept="image/*" placeholder="{{tr('site_icon')}}">

                                            @if(Setting::get('site_icon'))

                                                <img class="img img-thumbnail m-b-20" style="width: 20%" src="{{Setting::get('site_icon')}}" alt="{{Setting::get('site_name')}}"> 

                                            @endif

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="site_logo">{{tr('site_logo')}} *</label>
                                            <p class="txt-warning">{{tr('png_image_note')}}</p>
                                            <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*" placeholder="{{tr('site_logo')}}">

                                            @if(Setting::get('site_logo'))

                                                <img class="img img-thumbnail m-b-20" style="width: 40%" src="{{Setting::get('site_logo')}}" alt="{{Setting::get('site_name')}}"> 

                                            @endif
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

                    <!-- Configuration settings start -->

                    <div class="tab-pane pad" id="configuration_settings" role="tabpanel">

                        <div class="box box-solid bg-black">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{tr('configuration_settings')}}</h3>
                            </div>
                            <form id="site_settings_save" action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data" role="form">

                                @csrf
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="currency">{{tr('token_symbol')}} *</label>
                                            <input type="text" class="form-control" id="currency" name="currency" placeholder="Enter {{tr('currency')}}" value="{{old('currency') ?: Setting::get('currency')}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="network_token">{{tr('network_token')}} *</label>
                                            <input type="text" class="form-control" id="network_token" name="network_token" placeholder="Enter {{tr('network_token')}}" value="{{old('network_token') ?: Setting::get('network_token')}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="network_token">{{tr('amount_conversion')}} *</label>

                                            <div class="d-flex align-items-center">
                                                <div class="col-md-5">
                                                    <label for="network_token">{{Setting::get('network_token')}} *</label>
                                                    <input type="text" class="form-control" id="network_token_amount" name="network_token_amount" placeholder="Enter {{tr('network_token_amount')}}" value="{{old('network_token_amount') ?: Setting::get('network_token_amount')}}">
                                                </div>

                                                <div class="col-md-2">
                                                    =
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="network_token">{{Setting::get('currency')}} *</label>
                                                    <input type="text" class="form-control" id="lp_convertion_amount" name="lp_convertion_amount" placeholder="Enter {{tr('lp_convertion_amount')}}" value="{{old('lp_convertion_amount') ?: Setting::get('lp_convertion_amount')}}">
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="admin_wallet_address">{{tr('admin_wallet_address')}} *</label>
                                            <input type="text" class="form-control" id="admin_wallet_address" name="admin_wallet_address" placeholder="Enter {{tr('admin_wallet_address')}}" value="{{Setting::get('admin_wallet_address')}}">
                                        </div>


                                        <div class="form-group col-md-6">

                                            <label for="api_key">{{tr('api_key')}}</label>
                                            <input type="text" class="form-control" id="api_key" name="ether_api_key" placeholder="{{tr('api_key')}}" value="{{Setting::get('ether_api_key')}}">

                                            
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="crypto_url">{{tr('crypto_url')}}</label>
                                            <input type="text" class="form-control" id="crypto_url" name="crypto_url" placeholder="{{tr('crypto_url')}}" value="{{Setting::get('crypto_url')}}">

                                            
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="exchange_url">{{tr('exchange_url')}}</label>
                                            <input type="text" class="form-control" id="exchange_url" name="exchange_url" placeholder="{{tr('exchange_url')}}" value="{{Setting::get('exchange_url')}}">

                                            
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="contract_address">{{tr('contract_address')}}</label>
                                            <input type="text" class="form-control" id="contract_address" name="contract_address" placeholder="{{tr('contract_address')}}" value="{{Setting::get('contract_address')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="network_id">{{tr('network_id')}}</label>
                                            <input type="number" min="0" step="any" class="form-control" id="network_id" name="network_id" placeholder="{{tr('network_id')}}" value="{{Setting::get('network_id')}}">
                                            
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="chain_id_hexacode">{{tr('chain_id_hexacode')}}</label>
                                            <input type="text" class="form-control" id="chain_id_hexacode" name="chain_id_hexacode" placeholder="{{tr('chain_id_hexacode')}}" value="{{Setting::get('chain_id_hexacode')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="min_stake_token">{{tr('min_stake_token')}}</label>
                                            <input type="text" class="form-control" id="min_stake_token" name="min_stake_token" placeholder="{{tr('min_stake_token')}}" value="{{Setting::get('min_stake_token')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="rpc_url">{{tr('rpc_url')}}</label>
                                            <input type="text" class="form-control" id="rpc_url" name="rpc_url" placeholder="{{tr('rpc_url')}}" value="{{Setting::get('rpc_url')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="chain_name">{{tr('chain_name')}}</label>
                                            <input type="text" class="form-control" id="chain_name" name="chain_name" placeholder="{{tr('chain_name')}}" value="{{Setting::get('chain_name')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="native_currency_name">{{tr('native_currency_name')}}</label>
                                            <input type="text" class="form-control" id="native_currency_name" name="native_currency_name" placeholder="{{tr('native_currency_name')}}" value="{{Setting::get('native_currency_name')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="native_currency_symbol">{{tr('native_currency_symbol')}}</label>
                                            <input type="text" class="form-control" id="native_currency_symbol" name="native_currency_symbol" placeholder="{{tr('native_currency_symbol')}}" value="{{Setting::get('native_currency_symbol')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="native_currency_decimals">{{tr('native_currency_decimals')}}</label>
                                            <input type="number" min="1" step="any" class="form-control" id="native_currency_decimals" name="native_currency_decimals" placeholder="{{tr('native_currency_decimals')}}" value="{{Setting::get('native_currency_decimals')}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="block_explorer_urls">{{tr('block_explorer_urls')}}</label>
                                            <input type="text" class="form-control" id="block_explorer_urls" name="block_explorer_urls" placeholder="{{tr('block_explorer_urls')}}" value="{{Setting::get('block_explorer_urls')}}">

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
    
                    <!-- Configuration settings end -->

                    <!-- Email settings start -->

                    <div class="tab-pane pad" id="email_settings" role="tabpanel">

                        <div class="box box-solid bg-black">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{tr('email_settings')}}</h3>
                            </div>
                            
                            <form id="site_settings_save" action="{{route('admin.env-settings.save')}}" method="POST">

                                @csrf
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            
                                            <label for="MAIL_MAILER">{{tr('MAIL_MAILER')}} *</label>
                                           
                                            <p class="text-muted">{{tr('MAIL_MAILER_note')}}</p>
                                            
                                            <input type="text" class="form-control" id="MAIL_MAILER" name="MAIL_MAILER" placeholder="Enter {{tr('MAIL_MAILER')}}" value="{{old('MAIL_MAILER') ?: $env_values['MAIL_MAILER'] }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="MAIL_HOST">{{tr('MAIL_HOST')}} *</label>
                                            <p class="text-muted">{{tr('mail_host_note')}}</p>

                                            <input type="text" class="form-control" id="MAIL_HOST" name="MAIL_HOST" placeholder="Enter {{tr('MAIL_HOST')}}" value="{{old('MAIL_HOST') ?: $env_values['MAIL_HOST']}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="MAIL_USERNAME">{{tr('MAIL_USERNAME')}} *</label>

                                            <p class="text-muted">{{tr('mail_username_note')}}</p>

                                            <input type="text" class="form-control" id="MAIL_USERNAME" name="MAIL_USERNAME" placeholder="Enter {{tr('MAIL_USERNAME')}}" value="{{old('MAIL_USERNAME') ?: $env_values['MAIL_USERNAME']}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="MAIL_PASSWORD">{{tr('MAIL_PASSWORD')}} *</label>

                                            <p class="text-muted" style="visibility: hidden;">{{tr('mail_username_note')}}</p>

                                            <input type="password" class="form-control" id="MAIL_PASSWORD" name="MAIL_PASSWORD" placeholder="Enter {{tr('MAIL_PASSWORD')}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="MAIL_FROM_NAME">{{tr('MAIL_FROM_NAME')}} *</label>

                                            <p class="text-muted">{{tr('MAIL_FROM_NAME_note')}}</p>

                                            <input type="text" class="form-control" id="MAIL_FROM_NAME" name="MAIL_FROM_NAME" placeholder="Enter {{tr('MAIL_FROM_NAME')}}" value="{{old('MAIL_FROM_NAME') ?: $env_values['MAIL_FROM_NAME']}}">
                                        </div>

                                        <div class="form-group col-md-6">

                                            <label for="MAIL_FROM_ADDRESS">{{tr('MAIL_FROM_ADDRESS')}} *</label>

                                            <p class="text-muted">{{tr('MAIL_FROM_ADDRESS_note')}}</p>

                                            <input type="text" class="form-control" id="MAIL_FROM_ADDRESS" name="MAIL_FROM_ADDRESS" placeholder="Enter {{tr('MAIL_FROM_ADDRESS')}}" value="{{old('MAIL_FROM_ADDRESS') ?: $env_values['MAIL_FROM_ADDRESS']}}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="MAIL_ENCRYPTION">{{tr('MAIL_ENCRYPTION')}} *</label>

                                            <p class="text-muted">{{tr('mail_encryption_note')}}</p>

                                            <input type="text" class="form-control" id="MAIL_ENCRYPTION" name="MAIL_ENCRYPTION" placeholder="Enter {{tr('MAIL_ENCRYPTION')}}" value="{{old('MAIL_ENCRYPTION') ?: $env_values['MAIL_ENCRYPTION']}}">
                                        </div>


                                        

                                        <div class="form-group col-md-6">
                                            
                                            <label for="MAIL_PORT">{{tr('MAIL_PORT')}} *</label>

                                            <p class="text-muted">{{tr('mail_port_note')}}</p>

                                            <input type="text" class="form-control" id="MAIL_PORT" name="MAIL_PORT" placeholder="Enter {{tr('MAIL_PORT')}}" value="{{old('MAIL_PORT') ?: $env_values['MAIL_PORT']}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="MAILGUN_SECRET">{{ tr('MAILGUN_SECRET') }}</label>
                                            <input type="text" class="form-control" name="MAILGUN_SECRET" id="MAILGUN_SECRET" placeholder="{{ tr('MAILGUN_SECRET') }}" value="{{old('MAILGUN_SECRET') ?: ($env_values['MAILGUN_SECRET'] ?? '') }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="MAILGUN_DOMAIN">{{ tr('MAILGUN_DOMAIN') }}</label>
                                            <input type="text" class="form-control" value="{{ old('MAILGUN_DOMAIN') ?: ($env_values['MAILGUN_DOMAIN'] ?? '')  }}" name="MAILGUN_DOMAIN" id="MAILGUN_DOMAIN" placeholder="{{ tr('MAILGUN_DOMAIN') }}">
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
    
                    <!-- Email settings end -->

                    <!-- social settings start -->

                    <div class="tab-pane pad" id="social_settings" role="tabpanel">

                        <div class="box box-solid bg-black">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{tr('social_settings')}}</h3>
                            </div>
                            <form id="site_settings_save" action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data" role="form">

                                @csrf
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="facebook_link">{{tr('facebook_link')}}</label>

                                            <input type="text" class="form-control" id="facebook_link" name="facebook_link" placeholder="Enter {{tr('facebook_link')}}" value="{{old('facebook_link') ?: Setting::get('facebook_link')}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="linkedin_link">{{tr('linkedin_link')}}</label>

                                            <input type="text" class="form-control" id="linkedin_link" name="linkedin_link" placeholder="Enter {{tr('linkedin_link')}}" value="{{old('linkedin_link') ?: Setting::get('linkedin_link')}}">
                                        </div>


                                        <div class="form-group col-md-6">

                                            <label for="twitter_link">{{tr('twitter_link')}}</label>

                                            <input type="text" class="form-control" id="twitter_link" name="twitter_link" placeholder="Enter {{tr('twitter_link')}}" value="{{old('twitter_link') ?: Setting::get('twitter_link')}}">

                                            
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="pinterest_link">{{tr('pinterest_link')}}</label>

                                            <input type="text" class="form-control" id="pinterest_link" name="pinterest_link" placeholder="Enter {{tr('pinterest_link')}}" value="{{old('pinterest_link') ?: Setting::get('pinterest_link')}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="instagram_link">{{tr('instagram_link')}}</label>

                                            <input type="text" class="form-control" id="instagram_link" name="instagram_link" placeholder="Enter {{tr('instagram_link')}}" value="{{old('instagram_link') ?: Setting::get('instagram_link')}}">
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
    
                    <!-- social settings end -->

                    <!-- contact_information start -->

                    <div class="tab-pane pad" id="contact_information" role="tabpanel">

                        <div class="box box-solid bg-black">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{tr('contact_information')}}</h3>
                            </div>
                            <form id="site_settings_save" action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data" role="form">

                                @csrf
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="copyright_content">{{tr('copyright_content')}} *</label>
                                            <input type="text" class="form-control" id="copyright_content" name="copyright_content" placeholder="Enter {{tr('copyright_content')}}" value="{{old('copyright_content') ?: Setting::get('copyright_content')}}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="contact_mobile">{{tr('contact_mobile')}} *</label>

                                            <input type="text" class="form-control" id="contact_mobile" name="contact_mobile" placeholder="Enter {{tr('contact_mobile')}}" value="{{old('contact_mobile') ?: Setting::get('contact_mobile')}}">
                                        </div>


                                        <div class="form-group col-md-6">

                                            <label for="contact_email">{{tr('contact_email')}} *</label>

                                            <input type="text" class="form-control" id="contact_email" name="contact_email" placeholder="Enter {{tr('contact_email')}}" value="{{old('contact_email') ?: Setting::get('contact_email')}}">

                                            
                                        </div>

                                        <div class="form-group col-md-6">
                                            
                                            <label for="contact_address">{{tr('contact_address')}} *</label>

                                            <input type="text" class="form-control" id="contact_address" name="contact_address" placeholder="Enter {{tr('contact_address')}}" value="{{old('contact_address') ?: Setting::get('contact_address')}}">
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
    
                    <!-- social settings end -->

                    <!-- other_settings start -->

                    <div class="tab-pane pad" id="other_settings1" role="tabpanel">

                        <div class="box box-solid bg-black">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{tr('other_settings')}}</h3>
                            </div>
                            <form id="site_settings_save" action="{{ route('admin.settings.save') }}" method="POST" enctype="multipart/form-data" role="form">

                                @csrf
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="google_analytics">{{tr('google_analytics')}}</label>
                                            <textarea class="form-control" id="google_analytics" name="google_analytics">{{Setting::get('google_analytics')}}</textarea>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="header_scripts">{{tr('header_scripts')}}</label>
                                            <textarea class="form-control" id="header_scripts" name="header_scripts">{{Setting::get('header_scripts')}}</textarea>
                                        </div>


                                        <div class="form-group col-md-6">

                                            <label for="body_scripts">{{tr('body_scripts')}}</label>
                                            <textarea class="form-control" id="body_scripts" name="body_scripts">{{Setting::get('body_scripts')}}</textarea>
                                            
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
    
                    <!-- social settings end -->

                    <!-- blockchain_connection start -->

                    <div class="tab-pane pad" id="blockchain_connection" role="tabpanel">

                        <div class="box box-solid bg-black">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{tr('blockchain')}}</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">

                                    <div class="form-group col-md-6">

                                        <label for="reward_percentage">{{tr('reward_percentage')}} *</label>

                                        <input type="number" min="1" step="any" class="form-control" id="reward_percentage" name="reward_percentage" placeholder="Enter {{tr('reward_percentage')}}" >

                                        <button class="btn btn-primary mt-10" id="setRewardPercentage">
                                            <i class="ft-x"></i> {{ tr('submit') }} 
                                        </button>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="reward_amount">{{tr('reward_amount')}} *</label>

                                        <input type="number" min="1" step="any" class="form-control" id="reward_amount" name="reward_amount" placeholder="Enter {{tr('reward_amount')}}" >

                                        <button class="btn btn-warning mt-10" id="addRewardAmount">
                                            <i class="ft-x"></i> {{ tr('submit') }} 
                                        </button>
                                    </div>

                                </div>
                                
                            </div>
                        </div>
                    
                    </div>
    
                    <!-- social settings end -->
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>

<script type="module">

    import Token from "{{asset('abis/Token.json')}}" assert { type: "json" };

    import StakingPool from "{{asset('abis/StakingPool.json')}}" assert { type: "json" };

    import RewardStaking from "{{asset('abis/RewardStaking.json')}}" assert { type: "json" };
    
    $(document).ready(function() {

        checkConnection();

        $("div.fansclub-tab-menu>div.list-group>a").click(function(e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.fansclub-tab>div.fansclub-tab-content").removeClass("active");
            $("div.fansclub-tab>div.fansclub-tab-content").eq(index).addClass("active");
        });
    });

    if(document.getElementById("setRewardPercentage") != null) {

        document.querySelector('#setRewardPercentage').addEventListener('click', function() {
            setRewardPercentage();
        });
    }

    if(document.getElementById("addRewardAmount") != null) {

        document.querySelector('#addRewardAmount').addEventListener('click', function() {
            addRewardAmount();
        });
    }

    let token;
    let account;
    let tokenBalance;
    let etherSwap;
    let stakingPool;

    let netID = 56;

    let chainIDhexacode = "0x38";

    let chainStatus = false;

    let rewardStaking;

    let tokenData;

    async function checkConnection(){

        let web3 = window.ethereum;

        // Check if browser is running Metamask
        console.log("checking connection");

        if (window.ethereum) {

          web3 = new Web3(window.ethereum);

        } else if (window.web3) {

          web3 = new Web3(window.web3.currentProvider);

        }

        try {
          const networkId = await web3.eth.net.getId();

          console.log("Networkid", networkId);

          if (networkId === Number(netID)) {

            await web3.eth.getAccounts().then(async (response) => {

              if (response.length > 0) {

                console.log("effect save");

                getStatkingPoolData();

              } 
              else {

                await window.ethereum.enable();

                getStatkingPoolData();

              }
            });
          } else {

            console.log("change network");

            changeNetwork()
          }
        } catch (e) {

          console.log("error"+ e);
        }
    };

    async function changeNetwork(){

        // MetaMask injects the global API into window.ethereum
        if (window.ethereum) {

          try {

            // check if the chain to connect to is installed
            await window.ethereum.request({
              method: "wallet_switchEthereumChain",
              params: [{ chainId: chainIDhexacode }], // chainId must be in hexadecimal numbers
            });

            location.reload();

          } catch (error) {
            // This error code indicates that the chain has not been added to MetaMask
            // if it is not, then install it into the user MetaMask
            if (error.code === 4902) {

              try {

                await window.ethereum.request({
                  method: "wallet_addEthereumChain",
                  params: [
                    {
                      chainId: "0x38",
                      rpcUrls: ["https://bsc-dataseed1.ninicoin.io"],
                      chainName: "Smart Chain - MainNet",
                      nativeCurrency: {
                        name: "Binance",
                        symbol: "BNB", // 2-6 characters long
                        decimals: 18,
                      },
                      blockExplorerUrls: ["https://.bscscan.com"],
                    },
                  ],
                });

                await window.ethereum.enable();

                console.log("Etherum enabled");

                getStatkingPoolData();

              } catch (addError) {

                console.error(addError);
              }
            }
            console.error(error);
          }
        } else {
          // if no window.ethereum then MetaMask is not installed
          alert(
            "MetaMask is not installed. Please consider installing it: https://metamask.io/download.html"
          );
        }
    };

    async function checkAccountChange() {

        window.ethereum.on("accountsChanged", async function (accounts) {

          const web3 = window.web3;

          const network = await web3.eth.net.getId();

          console.log("network", network);

          if (network !== Number(netID)) {

            //must be on mainnet or Testnet
            console.log("Only this");

            getStatkingPoolData();

            changeNetwork();

          } else {

            //Do this check to detect if the user disconnected their wallet from the Dapp
            if (accounts && accounts[0]) getStatkingPoolData();

            else {

              getStatkingPoolData()

            }

          }
        });

        window.ethereum.on("chainChanged", (chainId) => {

          console.log("chain changed. ");

          chainStatus = true;
         
        });
    };

    async function getStatkingPoolData() {

        const web3 = new Web3(window.ethereum);

        const accounts = await web3.eth.getAccounts();

        console.log("Accounts", accounts[0]);

        account = accounts[0];

        const networkId = await web3.eth.net.getId();

        const rewardStakingPool = RewardStaking.networks[networkId];
        if (rewardStakingPool) {
          rewardStaking = new web3.eth.Contract(
            RewardStaking.abi,
            rewardStakingPool.address
          );

          tokenData = new web3.eth.Contract(
            Token.abi,
            "0xf729f4D13A9C9556875D20BACf9EBd0bF891464c"
          );

          let stakingBalance = await rewardStaking.methods
            .balanceOf(account)
            .call();

          console.log("Staking Balance", stakingBalance);

          let totalstakingBalance = await rewardStaking.methods
            .totalSupply()
            .call();

          console.log("Total supple", totalstakingBalance);

          let rewardRate = await rewardStaking.methods
            .rewardRate()
            .call();

          console.log("Reward rate", rewardRate);

          let numberOfStakers = await rewardStaking.methods
            .numberOfStakers()
            .call();

          console.log("numberOfStakers", numberOfStakers);
          console.log("Staking poll", rewardStaking._address);
        } else {
          window.alert("rewardStaking contract not deployed to detected network.");
        }
      };


     // set reward percentage. 

    function setRewardPercentage() {

        var rewardPercentage = $('#reward_percentage').val();

        console.log("rewardPercentage"+rewardPercentage);
 
        rewardStaking.methods
          .setRewardRate(rewardPercentage)
          .send({ from: account })
          .once("receipt", async (receipt) => {
            // Success notification. 
          })
          .on("error", (error) => {
            let notificationMessage;
            if (error.message == undefined) {
              notificationMessage = (
                "Unexpected error occuried, Please try again..."
              );
            } else {
              notificationMessage = (error.message);
            }
            // Fail notification. 
          });
    };

    // Add reward amount.  

    function addRewardAmount() {
        
        var amount = $('#reward_amount').val();

        console.log("addRewardAmount"+amount);

        const web3 = new Web3(window.ethereum);

        // Decimal
        const decimals = web3.utils.toBN(18);

        // Amount of token
        const tokenAmount = web3.utils.toBN(amount);

        // Amount as Hex - contract.methods.transfer(toAddress, tokenAmountHex).encodeABI();
        const tokenAmountHex = '0x' + tokenAmount.mul(web3.utils.toBN(10).pow(decimals)).toString('hex');

        console.log("tokenAmountHex"+tokenAmountHex);

        tokenData.methods
          .approve(rewardStaking._address, tokenAmountHex)
          .send({ from: account })
          .on("receipt", (receipt) => {
            rewardStaking.methods
              .addGrandAmount(tokenAmountHex)
              .send({ from: account })
              .once("receipt", async (receipt) => {
                // Success notification. 
              })
              .on("error", (error) => {
                let notificationMessage;
                if (error.message == undefined) {
                  notificationMessage = (
                    "Unexpected error occuried, Please try again..."
                  );
                } else {
                  notificationMessage = (error.message);
                }
                // Fail notification. 
              }) })
              .on("error", (error) => {
                let notificationMessage;
                if (error.message == undefined) {
                  console.log("Unexpected error occuried, Please try again...")
                } else {
                  console.log(error.message);
                }
              });
    };


</script>
@endsection