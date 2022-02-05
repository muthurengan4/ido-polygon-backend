<section class="content-header">
    <h1>
        @yield('content-header') <small>@yield('content-subheader')</small>
    </h1>
    <ol class="breadcrumb">

        <li class="breadcrumb-item">
            <a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a>
        </li>

        @yield('breadcrumb')
    </ol>
</section>