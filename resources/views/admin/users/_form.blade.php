<div class="row">

    <div class="col-lg-12 col-12">

        <div class="box">
            <div class="box-header with-border">
                
                <h3 class="box-title">{{ $user->id ? tr('edit_user') : tr('add_user') }}</h3>

            </div>
                
            <form action="{{ (Setting::get('is_demo_control_enabled') == YES) ? '#' : route('admin.users.save') }}" method="POST" enctype="multipart/form-data" role="form">
                
                @csrf

                <div class="box-body">

                    <div class="row">

                        <input type="hidden" name="user_id" id="user_id" value="{{ $user->id}}">

                        <div class="col-md-6">
                                
                            <label for="name">{{ tr('name') }}*</label>

                            <div class="form-group">                                  
                                
                                <input type="text" id="name" name="name" class="form-control" placeholder="{{ tr('name') }}" value="{{old('name') ?: $user->name}}" required onkeydown="return alphaOnly(event);">

                            </div>

                        </div>

                        <div class="col-md-6">
                                
                            <label for="wallet_address">{{ tr('wallet_address') }}*</label>

                            <div class="form-group">                                  
                                
                                <input type="text" id="wallet_address" name="wallet_address" class="form-control" placeholder="{{ tr('wallet_address') }}" value="{{old('wallet_address') ?: $user->wallet_address}}" required>

                            </div>

                        </div>

                        <!-- <div class="col-md-6">
                                
                            <label for="username">{{ tr('username') }}*</label>

                            <div class="form-group">                                  
                                
                                <input type="text" id="username" name="username" class="form-control" placeholder="{{ tr('username') }}" value="{{old('username') ?: $user->username}}" required>

                            </div>

                        </div>

                        <div class="col-md-6">
                                
                            <label for="email">{{tr('email')}}*</label>

                            <div class="form-group">          
                                
                                <input type="email" id="email" name="email" class="form-control" placeholder="{{tr('email')}}" value="{{ $user->email ?: old('email') }}" required pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                                oninvalid="this.setCustomValidity(&quot;{{ tr('email_validate') }}&quot;)"
                                oninput="this.setCustomValidity('')">

                            </div>

                        </div> -->

                        <!-- <div class="col-md-6">

                            <label for="mobile">{{ tr('mobile') }}</label>

                            <div class="form-group">
                                <input type="number" minlength="10" maxlength="12" class="form-control" pattern="[0-9]{6,13}" id="mobile" name="mobile" placeholder="{{ tr('mobile') }}" value="{{ old('mobile') ?: $user->mobile}}"/>
                            </div>
                        </div> -->

                        @if(!$user->id)

                            <div class="col-md-6">  

                                <label for="password" class="">{{ tr('password') }} *</label>

                                <div class="form-group">
                                    <input type="password" minlength="6" required name="password" class="form-control" id="password" placeholder="{{ tr('password') }}" >
                                </div>

                            </div>

                            <div class="col-md-6">

                                <label for="confirm-password" class="">{{ tr('confirm_password') }} *</label>

                                <div class="form-group">
                                    <input type="password" minlength="6" required name="password_confirmation" class="form-control" id="confirm-password" placeholder="{{ tr('confirm_password') }}">
                                </div>
                            </div>


                        @endif

                        <div class="col-md-6">

                            <label>{{ tr('select_picture') }}</label>
                            <p class="text-muted mt-0 mb-0">{{tr('image_validate')}}</p>
                            <div class="form-group">

                                <input type="file" class="form-control"  id="picture" name="picture"  accept="image/png, image/jpg"  >
                            
                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer">
                    
                    <button type="reset" class="btn btn-warning btn-default btn-squared px-30">Reset</button>

                    <button type="submit" class="btn btn-info pull-right">Submit</button>

                </div>

            </form>

        </div>

    </div>

</div>