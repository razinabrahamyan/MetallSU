<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!--favicon-->
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#3380cc">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#3380cc">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#3380cc">

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('user_assets/images/meta/favicon.ico')}}">
    <link rel="apple-touch-icon" href="{{asset('user_assets/images/meta/apple-touch-icon.png')}}">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('favicon-32x32.png')}}" type="image/png">
    <link rel="icon" href="{{asset('maskable_icon_x128.png')}}" type="image/png">
    <link rel="canonical" href="{{url()->full()}}">
    <link rel="manifest" href="{{asset('manifest.json')}}">

    <!--plugins-->
    <link href="{{asset('user_assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet"/>
    <link href="{{asset('user_assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet"/>
    <link href="{{asset('user_assets/plugins/input-tags/css/tagsinput.css')}}" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('user_assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('user_assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- loader-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="{{asset('user_assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{asset('user_assets/css/app.css?v=3')}}" rel="stylesheet">
    <link href="{{asset('user_assets/css/icons.css')}}" rel="stylesheet">
    <script src="{{asset('user_assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('user_assets/js/app.js')}}"></script>
    <script src="{{asset('user_assets/js/pullToRefresh.js')}}"></script>
    <link href="{{asset('user_assets/css/style.css?v=10')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('user_assets/plugins/notifications/css/lobibox.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/new/style.css?v=2')}}">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('styles')
    <title>M1-SU</title>
</head>
