<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @guest
    @else

    <!-- Latest compiled and minified CSS -->
    <link defer rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css')}}">

    <!-- Latest compiled and minified JavaScript -->
    <script defer src="{{asset('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js')}}"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    {{-- <script defer src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js')}}"></script> --}}

    <!--web fonts-->
    <link href="//fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <!--bootstrap styles-->
    {{-- <link href="{{ asset('plugins/kendo/css/kendo.common.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    {{-- <link href="{{ asset('plugins/kendo/css/kendo.default.min.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('plugins/kendo/css/kendo.default.mobile.min.css') }}" rel="stylesheet"> --}}

    <link href="{{ asset('assets/vendor/bootstrap2/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap2/css/bootstrap-glyphicons.css')}}" rel="stylesheet">

    <!--icon font-->
    <link href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/dashlab-icon/dashlab-icon.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-line-icons/css/simple-line-icons.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/themify-icons/css/themify-icons.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/weather-icons/css/weather-icons.min.css')}}" rel="stylesheet">

    <!--custom scrollbar-->
    <link href="{{ asset('assets/vendor/m-custom-scrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet">

    <!--slide pips -->
    <link href="{{ asset('assets/vendor/bootstrap2/Slidebar/dist/jquery-ui-slider-pips.min.css')}}" rel="stylesheet">

    <!--jquery dropdown-->
    <link href="{{ asset('assets/vendor/jquery-dropdown-master/jquery.dropdown.css')}}" rel="stylesheet">
    <!--jquery ui-->
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">

    <!--iCheck-->

    <!--custom styles-->
    <link href="{{ asset('assets/css/main.css')}}" rel="stylesheet">
    <!--alert -->
    <link href="{{ asset('assets/vendor/toastr-master/toastr.css')}}" rel="stylesheet">

    <!--select2-->
    <link href="{{ asset('assets/vendor/select2/css/select2.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/datetime-picker/css/datetimepicker.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/date-picker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/timepicker/css/timepicker.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/colorpicker/css/bootstrap-colorpicker.css')}}" rel="stylesheet">

    <link href="{{ asset('assets/vendor/bootstrap2/select/select.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap2/select/select.bootstrap4.min.css')}}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.4.0/jszip.min.js"></script>
    <link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet">
    {{-- alertify --}}
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>

    <!--Leaflet (Mapas)-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    <!--XSLX Plugin (Excel)-->
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js"
    ></script>

    <style>
        /* The check container */
        .check-container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            top: 4px;
            left: 4px;
        }

        /* Hide the browser's default checkbox */
        .check-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .check-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .check-container input:checked ~ .checkmark {
            background-color: #0f6a01;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .check-container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .check-container .checkmark:after {
            left: 10px;
            top: 5px;
            width: 6px;
            height: 14px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .form-outline{
            position: relative;
        }

        .form-arrow{
            color: #000;
            text-align: center;
            font-size: .8rem;
            position: absolute;
            right: 9px;
            top: 9px;
        }

        .list-select{
            width: 250px;
            height: 150px;
            padding: 0;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0px 1px 5px 0px;
            position: absolute;
            display: none;
            z-index: 1;
            background: white;
        }

        .form-control-select{
            min-width: 250px;
        }

        input.form-control.form-control-select:focus~.list-select {
            display: block;
        }

        .list-select:hover {
            display: block;
        }

        .select-item{
            height: 35px;
        }

        .select-item:hover{
            background: #ddd;
        }
        
        /* SECCION DEL CUESTIONARIO */
        .title-question {
            font-size: 26px;
        }

        input[type="radio"] {
            display: none;
        }

        input[type="radio"]:checked + label {
            background-color: #3dba6f;
            color: #fff;
        }

        .questionnaire-graphic {
            margin-top: 30px;
            padding: 5px;
            border: 1px solid #d5d8db;
            border-radius: 4px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 24px;
        }

        .pdf-logo {    
            height: 60px;
            margin: 15px 0 10px;
        }

        .question-header {
            /* background: #313131;
            color: #f1f1f1; */
            border-bottom: 1px solid #3232322e;
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .question-header span {
            margin-right: 15px;
        }

        .question-header span span {
            font-weight: bold;
        }
        

        .question {
            margin: 15px 0 3px;
        }


        canvas {
            margin-bottom: 25px;
        }

        .button-xlsx {
            border: 1px solid hsl(209, 14%, 30%);
            color: #232323;
        }
        .button-xlsx::after {
            background-color: #197fff;
            color: #fff;
            right: -1px;
        }
    </style>
  @endguest
</head>

@guest
@yield('content-login')
@else

<body class="left-side-toggled">

<!--navigation : sidebar & header-->
<nav class="navbar navbar-expand-lg fixed-top navbar-light" id="mainNav">

    <!--brand name-->
    <a class="navbar-brand" href="https://buextra.hartec.com.mx/" data-jq-dropdown="#jq-dropdown-1">
        <img class="pr-3 float-left img-res-nav" style="height: 5vh; min-height: 10px;" src="{{ asset('img/logot.png')}}" alt=""/>
    </a>
    <!--/brand name-->

    <!--brand mega menu-->

    <!--/brand mega menu-->

    <!--responsive nav toggle-->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!--/responsive nav toggle-->

    <!--responsive rightside toogle-->

    <!--/responsive rightside toogle-->
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <!--left side nav-->
        <ul class="navbar-nav left-side-nav" id="accordion">
            @foreach ($secciones as $seccion)
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="{{$seccion->description}}">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" data-target="#multi_menu{{$seccion->section}}">
                        <i class="{{$seccion->icon}}" ></i>
                            <span class="nav-link-text">{{$seccion->section}}</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="multi_menu{{$seccion->section}}" data-parent="#accordion">
                        @if(count($subsections->where('padre_id',$seccion->id))>0)
                            @foreach ($subsections as $sub)
                                @if ($seccion->id == $sub->padre_id)
                                    <li class="nav-item" data-toggle="tooltip" data-placement="right">
                                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" data-target="#multi_menu_sub{{$sub->subsection}}">
                                            <span class="nav-link-text">{{$sub->subsection}}</span>
                                        </a>
                                        <ul class="sidenav-third-level collapse" id="multi_menu_sub{{$sub->subsection}}" data-parent="multi_menu_{{$seccion->section}}">
                                        @foreach ($user_permissions as $module)
                                        @if ($module->reference == $sub->sub_section_id )
                                            <li>

                                            @if($module->url!=null)
                                                <a class="permisions_id " href="{{ route($module->url) }}">{{$module->module}}</a>
                                            @else
                                                <a href="#">{{$module->module}}</a>
                                            @endif
                                            </li>
                                        @endif
                                        @endforeach

                                    </ul>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            @foreach ($user_permissions as $module)
                                @if ($module->reference == $seccion->id )

                                    <li>

                                    @if($module->url!=null)
                                        <a class="permisions_id " href="{{ route($module->url) }}">{{$module->module}}</a>
                                    @else
                                        <a href="#">{{$module->module}}</a>
                                    @endif
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endforeach
        </ul>
        <!--/left side nav-->

        <ul class="navbar-nav header-links ml-auto hide-arrow">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mr-lg-3" id="userNav" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-thumb">
                        <img class="rounded-circle" src="{{ asset('assets/img/avatar/avatar1.jpg')}}" alt="">
                        <span>{{ Auth::user()->name }} </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userNav">
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"> {{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>

            </li>

        </ul>
        <!--nav push link-->
        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="left-nav-toggler">
                    <i class="fa fa-angle-left" style="color:#ffffff; font-weight: bold" ></i>
                </a>
            </li>
        </ul>
        <!--/nav push link-->

        <!--header leftside links-->

        <!--/header leftside links-->

        <!--header rightside links-->



        <!--/header rightside links-->

    </div>
</nav>


<!--/navigation : sidebar & header-->

<!--main content wrapper-->
<div class="content-wrapper">
    @yield('content')
</div>
<!--/main content wrapper-->

<!--right sidebar-->

<!--/right sidebar-->

<!--basic scripts-->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{ asset('assets/vendor/popper.min.js')}}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('assets/vendor/jquery-dropdown-master/jquery.dropdown.js')}}"></script>
<script src="{{ asset('assets/vendor/m-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{ asset('assets/vendor/icheck/skins/icheck.min.js')}}"></script>
{{-- <script src="{{ asset('plugins/kendo/js/kendo.all.min.js') }}"></script> --}}
{{-- <script src="{{ asset('plugins/kendo/js/pako_deflate.min.js') }}"></script> --}}
{{-- <script src="{{ asset('plugins/kendo/js/jquery.number.min.js') }}"></script> --}}
{{-- <script src="{{ asset('plugins/kendo/js/kendo.culture.es-ES.min.js') }}"></script> --}}
{{-- <script src="{{ asset('plugins/kendo/js/kendo.messages.es-MX.min.js') }}"></script> --}}
<script src="{{ asset('js/functions.js') }}"></script>

<!--Otros-->
<script src="{{ asset('assets/vendor/bootstrap2/Slidebar/dist/jquery-ui-slider-pips.js')}}"></script>
<script src="{{ asset('assets/vendor/bootstrap2/Slidebar/dist/jquery-ui-slider-pips.js')}}"></script>

<script src="{{ asset('assets/vendor/bootstrap2/jsbarcode/JsBarcode.js')}}"></script>
<script src="{{ asset('assets/vendor/bootstrap2/jsbarcode/CODE128.js')}}"></script>


<script src="{{ asset('assets\vendor\bootstrap2\select\select.bootstrap4.js')}}"></script>

<script src="{{ asset('assets\vendor\bootstrap2\select\select.bootstrap4.min.js')}}"></script>

<!--SweetAlert-->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--alert -->
<script src="{{ asset('assets/vendor/toastr-master/toastr.js')}}"></script>

<!--select2-->
<script src="{{ asset('assets/vendor/select2/js/select2.min.js')}}"></script>

<!--[if lt IE 9]>
<script src="{{ asset('assets/vendor/modernizr.js')}}"></script>
<![endif]-->

<!--Bootstrap2-->
<script src="{{ asset('assets/vendor/bootstrap2/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{ asset('assets/vendor/bootstrap2/js/dropdown.js')}}"></script>
<script src="{{ asset('assets/vendor/bootstrap2/js/dist/js/bootstrap-checkbox.js') }}" defer></script>

<!--BootstrapTable-->
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>

<!--basic scripts initialization-->
<script src="{{ asset('assets/js/scripts.js')}}"></script>
@yield('js')
{{-- <script type="x/kendo-template" id="page-template">
    <div class="page-template">
    </div>
</script>
<script type="text/x-kendo-template" id="windowTemplate">
    <p>Desea eliminar el registro <strong>#= name #</strong> ?</p>
    <button class="k-button k-button-icontext k-primary" id="yesButton"><span class="k-icon k-i-check"></span> Si</button>
    <button class="k-button  k-button-icontext k-button" id="noButton"><span class="k-icon k-i-cancel"></span> No</button>
</script> --}}
<script>
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}
    });
    // kendo.culture("es-MX");
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    $.extend($.fn.bootstrapTable.defaults.icons, {
    clearSearch: 'fa-eraser'
  });
</script>
@endguest
</body>
</html>
