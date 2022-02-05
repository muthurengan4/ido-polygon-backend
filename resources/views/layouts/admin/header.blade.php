<header class="main-header">
    <!-- Logo -->
    <a href="{{route('admin.dashboard')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <b class="logo-mini">
        <span class="light-logo"><img src="{{Setting::get('site_logo_light')}}" alt="logo"></span>
        <span class="dark-logo"><img src="{{Setting::get('site_logo')}}" alt="logo"></span>
        </b>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
        <img src="{{Setting::get('site_icon')}}" alt="logo" class="light-logo">
        <img src="{{Setting::get('site_icon')}}" alt="logo" class="dark-logo">
        </span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ Auth::guard('admin')->user()->picture ? Auth::guard('admin')->user()->picture : asset('placeholder.jpeg')}}" class="user-image rounded-circle">
                        <span class="user-name">{{Auth::guard('admin')->user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu scale-up">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{Auth::guard('admin')->user()->picture}}" style="width:90px" class="float-left rounded-circle">
                            <p>
                                {{Auth::guard('admin')->user()->name}}
                                <small class="mb-5">{{Auth::guard('admin')->user()->email}}</small>
                                <small class="mb-5">{{Auth::guard('admin')->user()->timezone}}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row no-gutters">
                                <div class="col-12 text-left">
                                    <a class="dropdown-item" href="{{route('admin.profile')}}">
                                        <i class="ion ion-person"></i> {{tr('account')}}
                                    </a>
                                </div>
                                <div role="separator" class="divider col-12"></div>
                                <div class="col-12 text-left">
                                    <a class="dropdown-item"  data-toggle="modal" data-target="#logoutModel" href="{{route('admin.logout')}}"><i class="fa fa-power-off"></i>{{tr('logout')}}</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                    </ul>
                </li>
                
            </ul>
        </div>
    </nav>
</header>