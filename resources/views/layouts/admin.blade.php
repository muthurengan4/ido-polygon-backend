<!doctype html>
<html lang="en" dir="ltr">

<head>
    
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>{{Setting::get('site_name')}}</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{Setting::get('site_icon') ?? asset('favicon.png')}}">

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap/dist/css/bootstrap.css')}}">
    <!--amcharts -->
    <link href="https://www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
    <!-- Bootstrap-extend -->
    <link rel="stylesheet" href="{{asset('css/bootstrap-extend.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.css')}}">

    <!-- theme style -->
    <link rel="stylesheet" href="{{asset('css/master_style.css')}}">
    <!-- Unique_Admin skins -->
    <link rel="stylesheet" href="{{asset('css/skins/_all-skins.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendor_plugins/iCheck/all.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendor_plugins/timepicker/bootstrap-timepicker.min.css')}}">

    <!-- daterange picker -->   
    <link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css')}}">
    
    <!-- bootstrap datepicker -->   
    <link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('styles')

    <!-- Start of LiveChat (www.livechatinc.com) code -->
    <!-- <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 13202628;
        ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can’t use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
    </script>
    <noscript><a href="https://www.livechatinc.com/chat-with/13202628/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript> -->
    <!-- End of LiveChat code -->

</head>

<body class="hold-transition skin-black-light sidebar-mini">

    <div class="wrapper">

        @include('layouts.admin.header')

        @include('layouts.admin.sidebar')

        <div class="content-wrapper">

            @include('layouts.admin.breadcrumb')

            @include('notifications.notify')
            
            <section class="content">
                @yield('content')
            </section>
        
        </div>

        @include('layouts.admin.footer')


    </div>

    <div class="control-sidebar-bg"></div>

    @include('layouts.admin._logout_model')

    @include('layouts.admin.scripts')

    @yield('scripts')

    <script type="text/javascript">

        $('.datetimepicker').datetimepicker({
            minDate: new Date(),
            format: 'YYYY-MM-DD HH:mm:ss',
            icons: {
                  time: "fa fa-clock-o",
                  date: "fa fa-calendar",
                  up: "fa fa-arrow-up",
                  down: "fa fa-arrow-down"
              },
              sideBySide: true
        });

        @if(isset($page)) 
            $("#{{$page}}").addClass("active");
        @endif

        @if(isset($sub_page)) 
            $("#{{$sub_page}}").addClass("active");
        @endif
        
    </script>

</body>

</html>