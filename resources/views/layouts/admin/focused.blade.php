<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta name="description" content="{{Setting::get('site_name')}}">

    <meta name="keywords" content="{{Setting::get('site_name')}}">
    
    <meta name="author" content="{{Setting::get('site_name')}}">
    
    <title>{{Setting::get('site_name')}}</title>  

    <meta name="robots" content="noindex">

    <link rel="apple-touch-icon" href="@if(Setting::get('site_logo')) {{ Setting::get('site_logo') }}  @else {{asset('admin-assets/images/ico/apple-icon-120.png') }} @endif">

    <link rel="shortcut icon" type="image/x-icon" href="{{Setting::get('site_icon')}}">
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap/dist/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('css/bootstrap-extend.css')}}">
    <!-- theme style -->
    <link rel="stylesheet" href="{{asset('css/master_style.css')}}">
    <!-- Unique_Admin skins -->
    <link rel="stylesheet" href="{{asset('css/skins/_all-skins.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.css')}}">

    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <!-- Start of LiveChat (www.livechatinc.com) code -->
    <!-- <script>
        window.__lc = window.__lc || {};
        window.__lc.license = 13202628;
        ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can’t use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
    </script>
    <noscript><a href="https://www.livechatinc.com/chat-with/13202628/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript> -->
    <!-- End of LiveChat code -->


</head>

<body class="hold-transition login-page">
    <section class="h-p100">
        <div class="container h-p100">
            <div class="row h-p100 align-items-center justify-content-md-center">
                @yield('content')
            </div>
        </div>
    </section>

    <!-- jQuery 3 -->
    <script src="{{asset('assets/vendor_components/jquery/dist/jquery.js')}}"></script>


    <script src="{{asset('js/pages/toastr.js')}}"></script>

    <script src="{{asset('js/pages/notification.js')}}"></script>
    <script src="{{asset('assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js')}}"></script>

    @yield('scripts')
</body>


</html>