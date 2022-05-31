<!-- Top Bar
		============================================= -->
<div id="top-bar" style="background-color: #F9F9F9">
    <div class="container clearfix">

        <div class="row justify-content-between">
            <div class="col-12 col-md-auto">

                <!-- Top Links
						============================================= -->
                <div class="top-links">
                    <ul class="top-links-container">
                        <li class="top-links-item"><a href="tel:{{ $config->phone }}"><i class="icon-phone3"></i> {{ $config->phone }}</a></li>
                        <li class="top-links-item"><a href="mailto:{{ $config->email }}" class="nott"><i class="icon-envelope2"></i>{{ $config->email }}</a></li>
                    </ul>
                </div><!-- .top-links end -->

            </div>

            <div class="col-12 col-md-auto">
                <!-- Top Social
                ============================================= -->
                <ul id="top-social">
                    <li><a href="https://www.facebook.com/fastsmsportal" class="si-facebook"><span class="ts-icon"><i class="icon-facebook"></i></span><span class="ts-text">Facebook</span></a></li>
                    <li><a href="#" class="si-twitter"><span class="ts-icon"><i class="icon-twitter"></i></span><span class="ts-text">Twitter</span></a></li>
                </ul><!-- #top-social end -->



            </div>
        </div>

    </div>
</div><!-- #top-bar end -->
<!-- Header
		============================================= -->
<header id="header" class="full-header">
    <div id="header-wrap">
        <div class="container">
            <div class="header-row">

                <!-- Logo
                ============================================= -->
                <div id="logo" class="flex-row">
                    <a href="{{ route('home') }}">
                    @if(!empty($config->logo) && file_exists("assets/images/".$config->logo))
                        <img class="standard-logo" alt="admin logo" src="{{ asset("assets/images/".$config->logo) }}">
                    @else
                        <img class="standard-logo" alt="admin logo" src="/assets/app-assets/images/logo/stack-logo-light.png">
                    @endif
                        <h2 class="brand-text">{{ $config->brand_name }}</h2>
                    </a>
                </div><!-- #logo end -->

                <div class="header-misc">

                    <!-- Top Cart
                    ============================================= -->
                    <div id="top-cart" class="header-misc-icon">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <a href="#" id="signUpNotify" class="social-icon si-small si-borderless si-facebook">
                                <i class="icon-signin"></i>
                                <i class="icon-sign-out-alt"></i>
                            </a>
                        </div>

                    </div><!-- #top-cart end -->

                </div>

                <div id="primary-menu-trigger">
                    <svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
                </div>

                @include('frontend.layouts.menu')

            </div>
        </div>
    </div>
    <div class="header-wrap-clone"></div>
</header><!-- #header end -->
