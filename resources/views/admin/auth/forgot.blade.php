@extends('layouts.admin.focused')

@section('title', tr('login'))

@section('content-header', tr('login'))

@section('content')
    
    <div class="col-lg-4 col-md-8 col-12">

        @include('notifications.notify')

        <div class="box box-body rounded">

            <h3 class="login-box-msg">{{Setting::get('site_name')}}</h3>

            <form action="{{ route('admin.forgot_password.update') }}" method="POST">

                @csrf

                <input type="hidden" name="timezone" value="" id="userTimezone">

                <div class="form-group has-feedback">

                    <input type="email" class="form-control" id="user-name" required placeholder="{{tr('email_address')}}" value="{{old('email') ?: Setting::get('demo_admin_email')}}" name="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" oninvalid="this.setCustomValidity(&quot;{{ tr('email_validate') }}&quot;)" oninput="this.setCustomValidity('')"
                        
                        >
                    <span class="ion ion-email form-control-feedback"></span>
                </div>

            
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12 text-center">
                      <button type="submit" class="btn bg-orange btn-block mb-15">{{tr('reset')}}</button>
                    </div>
                    <div class="col-12 text-center">
                         <a href="{{route('admin.login')}}" class="btn bg-orange btn-block mb-15">
                           {{tr('login')}}
                        </a>
                    </div>
                </div>
            </form>

        </div> 

    </div>


@endsection

@section('scripts')

<script src="{{asset('js/jstz.min.js')}}"></script>
<script>
    $(document).ready(function() {

        var dMin = new Date().getTimezoneOffset();
        var dtz = -(dMin/60);
        // alert(dtz);
        $("#userTimezone").val(jstz.determine().name());
    });

</script>

@endsection

