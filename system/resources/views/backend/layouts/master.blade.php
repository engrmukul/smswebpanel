<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta charset="utf-8">
    <meta name="author" content="A S M Saief">
    <meta name="csrf-token" id="_token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>@yield('title') - {{ $config->brand_name }}</title>

    @if(!empty($config->logo) && file_exists("assets/images/".$config->logo))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/'.$config->logo) }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/images/'.$config->logo) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/app-assets/images/ico/favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/app-assets/images/ico/apple-icon-120.png') }}">
    @endif
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/vendors.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Page Vendor CSS-->
    @yield('pageVendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <!-- END: Page Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/components.css') }}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/fonts/simple-line-icons/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/pages/timeline.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/core/colors/palette-gradient.css') }}">
@yield('pageCSS')
<!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link href='{{ asset("assets/css/print.css") }}' rel="stylesheet" type="text/css" media="print"/>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />

@yield('customCSS')
    <style>
        .loadingDiv {
            /*visibility: hidden;*/
            display: none;
            background-color: rgba(255,255,255,0.7);
            position: fixed;
            z-index: +9999 !important;
            width: 100%;
            top: 0;
            left: 0;
            height:100%;
        }

        .loadingDiv img {
            position: fixed;
            top: 35%;
            left:50%;
        }

        .bootstrap-tagsinput{
            width: 100%;
            min-height: 200px !important;
        }
        .label-info{
            background-color: #17a2b8;

        }
        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,
            border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>
<!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
<!-- Top Header -->
@include('backend.layouts.header')
<!-- /.Top Header -->
<!-- Left side column. contains the logo and sidebar -->
@include('backend.layouts.menu')
<!-- /.Left side column. contains the logo and sidebar -->

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            @yield('breadcrumb')
        </div>
        <div class="content-body">
            <section id="content-message" style="clear: both;">
                <div>
                    <div class="col-md-12">
                        <div class="script-message"></div>
                        @if(Session::has('message'))
                            <div class="alert {{ Session::get('m-class') }} alert-dismissible"
                                 style="margin-top: 10px;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                                {!! Session::get('message') !!}
                            </div>
                        @endif

                    </div>
                </div>
            </section>
            @yield('content')
        </div>
    </div>
</div>
<!-- END: Content-->


<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
@php($thisYear = (int)date('Y'))
<footer class="footer fixed-bottom footer-static footer-dark navbar-border">
    <p class="clearfix lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2020 - {{ $thisYear }} <a class="text-bold-800 darken-2" href="{{ route('dashboard') }}" target="_blank">{{ $config->brand_name }}</a></span><span class="float-md-right d-none d-lg-block">Hand-crafted & Made with <i class="feather icon-heart pink"></i></span></p>
</footer>
<!-- END: Footer-->

<div id="loadingDiv" class="loadingDiv">
    <img width='75px' src='{{ asset('assets/images/loading.gif') }}' alt="Loading...">
</div>


<!-- BEGIN: Vendor JS-->
<script src="{{ asset('assets/app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
@yield('pageVendorJS')
<script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('assets/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('assets/app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('assets/js/ajaxload.js') }}"></script>
<!-- END: Theme JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js" integrity="sha512-VvWznBcyBJK71YKEKDMpZ0pCVxjNuKwApp4zLF3ul+CiflQi6aIJR+aZCP/qWsoFBA28avL5T5HA+RE+zrGQYg==" crossorigin="anonymous"></script>

<!-- BEGIN: Page JS-->
@yield('pageJS')
<!-- END: Page JS-->

<!-- BEGIN: Custom JS-->
@yield('customJs')
<script type="text/javascript">
    function myDeleteFunction(name, url, cat) {
        if (confirm("Are you sure to delete (" + name + "\'s) " + cat + "?")) {
            event.preventDefault();
            document.getElementById('delete').action = url;
            document.getElementById('delete').submit();
        } else {
            event.preventDefault();
        }
    }

    $(document).ready(function () {
        $('.ajax-loading').loadAjaxContents();
    });

</script>

<!-- BEGIN: Custom JS-->
<script type="text/javascript">
    $(document).ready(function () {
        var frm = $('.form');
        frm.submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                enctype: 'multipart/form-data',
                type: 'POST',
                data: formData,
                success: function (data) {
                    data = JSON.parse(data);
                    $('.modal').modal('toggle');
                    $('.modal .close').click();
                    $.get(data.url).done(function (page_content) {
                        $('.app-content').html(page_content);
                    });
                },
                error: function (reject) {
                    var errors = reject.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        $parentDiv = $('[name^="' + key + '"]').parents('.input-element');
                        $parentDiv.addClass('has-error');
                        $parentDiv.find('.text-danger').html(val);
                    });
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        $('button[link]').on('click', function (e) {
            var elem = '.app-content';
            var load_url = $(this).attr('link');
            if (!load_url) {
                return true;
            }
            window.history.pushState('', '', load_url);
            $.get(load_url).done(function (page_content) {
                $(elem).html(page_content);
            }).fail(function (data, textStatus, xhr) {
                window.location.href = "/smspanel/dashboard";
            });
            return false;
        });
    });
</script>

<script>
    // jQuery plugin to prevent double click
    jQuery.fn.preventDoubleClick = function () {
        $(this).on('click', function (e) {
            var $el = $(this);
            if ($el.data('clicked')) {
                // Previously clicked, stop actions
                e.preventDefault();
                e.stopPropagation();
            } else {
                // Mark to ignore next click
                $el.data('clicked', true);
                // Unmark after 1 second
                window.setTimeout(function () {
                    $el.removeData('clicked');
                }, 2000)
            }
        });
        return this;
    };
    $('.btn').preventDoubleClick();
</script>
<!-- END: Custom JS-->

</body>
<!-- END: Body-->

</html>
