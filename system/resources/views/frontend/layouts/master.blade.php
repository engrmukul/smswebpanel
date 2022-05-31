<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<!-- BEGIN: Head-->

<head>
    <meta name="author" content="Fast SMS Portal">
    <meta name="csrf-token" id="_token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Meta -->
    <meta name="keywords" content="bulk sms bd, bulk Sms, sms service bd, sms service, Bulk SMS marketing BD, Bulk SMS Software, Bulk SMS for campaign, Bulk SMS for Election, Corporate Bulk SMS, Corporate SMS Service, Bulk SMS for Business, Free Bulk SMS Site, Send SMS online, Bangla SMS, Bundle SMS, Send Free SMS, Bulk SMS Provider Bangladesh, Send SMS to BD Birthday Wish SMS, Excel SMS"/>
    <meta name="robots" content=""/>
    <meta name="description" content="FastSMSPortal Bulk SMS Service is one of the top most bulk SMS service/gateway provider in Bangladesh. You can send free online SMS using our service. You can send SMS from Excel file. We have dedicated server and multiple gateway backup system. People use our service for bulk SMS sending, Marketing SMS Sending, Notice/Alert SMS sending, Exam result SMS sending, Election Campaign SMS etc."/>
    <meta property="og:title" content="FastSMSPORTAL Bulk SMS Service"/>
    <meta property="og:description" content="We are providing Bulk SMS Service in Bangladesh"/>

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
    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/dark.css') }}" type="text/css"/>

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/swiper.css') }}" type="text/css"/>

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/font-icons.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/animate.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/magnific-popup.css') }}" type="text/css"/>

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custom.css') }}" type="text/css"/>

    <!-- BEGIN: Page CSS-->
    @yield('pageCSS')
    <!-- END: Page CSS-->
    <style>
        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 80% !important;
            }
        }
        .modal-body {
            padding: 3rem !important;
        }
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="stretched">
    <div id="wrapper" class="clearfix">
        <!-- Top Header -->
        @include('frontend.layouts.header')
        <!-- /.Top Header -->

        <!-- BEGIN: Content-->
        @yield('content')
        <!-- END: Content-->

        @include('frontend.layouts.footer')
    </div>

    <!-- Go To Top
    ============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>

    <!-- JavaScripts
    ============================================= -->
    <script src="{{ asset('assets/frontend/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/plugins.min.js') }}"></script>

    <!-- Footer Scripts
    ============================================= -->
    <script src="{{ asset('assets/frontend/js/functions.js') }}"></script>


    <!-- BEGIN: Page JS-->
    @yield('pageJS')
    <script>
        // Get the modal
        var modal = document.getElementById('notifyTerms');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        $(document).on('click', '#signUpNotify', function (event){
            event.preventDefault();
            $('#notifyTerms').modal('show');
        });
    </script>
</body>
<!-- END: Body-->

</html>
