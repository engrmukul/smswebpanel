@extends('frontend.layouts.master')

@section('title')
    Home
@endsection

@section('content')
    @include('frontend.layouts.slider')

    <!-- Content
		============================================= -->
    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">

               <div class="mx-auto center clearfix" style="max-width: 900px;">
{{--                    <img class="bottommargin" src="{{ asset('assets/frontend/images/logo-side.png') }}" alt="Image">--}}
                    <h1>Welcome! <span>{{ $config->brand_name }}</span>.</h1>
                    <h2>Use advanced SMS strategy to get more quantity of leads.</h2>
                </div>

                <div class="line"></div>

                <div class="row justify-content-center col-mb-50">
                    <div class="col-sm-6 col-lg-4">
                        <div class="feature-box fbox-sm fbox-plain" data-animate="fadeIn">
                            <div class="fbox-icon">
                                <a href="#"><i class="icon-shield"></i></a>
                            </div>
                            <div class="fbox-content">
                                <h3>End to End Encryption</h3>
                                <p>Fast SMS ensures the end-end-encryption.The messages and mobile numbers will encrypted by the system and stores them as encrypted but the associated account users and recipients will get the decrypted data.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="feature-box fbox-sm fbox-plain" data-animate="fadeIn" data-delay="200">
                            <div class="fbox-icon">
                                <a href="#"><i class="icon-eye"></i></a>
                            </div>
                            <div class="fbox-content">
                                <h3>Dipping Query</h3>
                                <p>We have the connectivity with Mobile number portability(MNP) aggregator, which ensures 100% Masking rates.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="feature-box fbox-sm fbox-plain" data-animate="fadeIn" data-delay="400">
                            <div class="fbox-icon">
                                <a href="#"><i class="icon-star2"></i></a>
                            </div>
                            <div class="fbox-content">
                                <h3>Dynamic SMS Campaign</h3>
                                <p>If you have the system generated report in xls/csv, use our dynamic SMS options for super personalized notification/SMS sending to your audiance.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="feature-box fbox-sm fbox-plain" data-animate="fadeIn" data-delay="600">
                            <div class="fbox-icon">
                                <a href="#"><i class="icon-rocket"></i></a>
                            </div>
                            <div class="fbox-content">
                                <h3>High TPS</h3>
                                <p>We have scalable platform with capability of handling of high volume of requests. We provides from 10 to 100 TPS per client as they have the demand.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="feature-box fbox-sm fbox-plain" data-animate="fadeIn" data-delay="800">
                            <div class="fbox-icon">
                                <a href="#"><i class="icon-thumbs-up"></i></a>
                            </div>
                            <div class="fbox-content">
                                <h3>Ease of use</h3>
                                <p>We have very friendly user interface (UI), so that our resellers and users can go through with minimal documentation. Resellers can aslo manage their own user base.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="feature-box fbox-sm fbox-plain" data-animate="fadeIn" data-delay="1000">
                            <div class="fbox-icon">
                                <a href="#"><i class="icon-params"></i></a>
                            </div>
                            <div class="fbox-content">
                                <h3>API & SMPP connectivity</h3>
                                <p>We have more flexibility interms of Integration with your system.We have both API and SMPP(Peer to Peer) integration options, so that user can choose their fit.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="clear"></div>

            <div class="section parallax dark mb-0 border-bottom-0" style="background-image: url({{ asset('assets/frontend/images/parallax/7.jpg') }});" data-bottom-top="background-position:0px 0px;" data-top-bottom="background-position:0px -300px;">

                <div class="container clearfix">

                    <div class="heading-block center">
                        <h2>{{ $config->brand_name }}: We know you want it!</h2>
                        <span>Best OTP & Marketing SMS Service Provider in Bangladesh</span>
                    </div>

                    <div style="position: relative; margin-bottom: -60px;" data-height-xl="415" data-height-lg="342" data-height-md="262" data-height-sm="160" data-height-xs="102">
                        <img src="{{ asset('assets/frontend/images/services/chrome.png') }}" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" alt="Chrome">
                        <img src="{{ asset('assets/frontend/images/services/ipad3.png') }}" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" data-delay="300" alt="iPad">
                    </div>

                </div>

            </div>


            <div class="row bottommargin-lg align-items-stretch">

                <div class="col-lg-4 dark col-padding overflow-hidden" style="background-color: #1abc9c;">
                    <div>
                        <h3 class="text-uppercase" style="font-weight: 600;">Why choose Us</h3>
                        <p style="line-height: 1.8;">FastSMS, is the leading SMS service provider in bangaladesh. We are providing 99.9% reach out with actual delivery report. We have 24/7 Support ensures uninterrupted services. We care about your data and leads thats why think of encrypted data storage. As a whole we're holding a number of reasons that you can choose us as tool for your marketing team.</p>
                        <i class="icon-bulb bgicon"></i>
                    </div>
                </div>

                <div class="col-lg-4 dark col-padding overflow-hidden" style="background-color: #34495e;">
                    <div>
                        <h3 class="text-uppercase" style="font-weight: 600;">Our Mission</h3>
                        <p style="line-height: 1.8;">In the age of communication, reaching out to your targeted customer or building a desired lead is much easier. We are working build up such a marketing tool, where user can design the workflow. The marketing tool will show the actual impact on business by using AI.</p>
                        <i class="icon-cog bgicon"></i>
                    </div>
                </div>

                <div class="col-lg-4 dark col-padding overflow-hidden" style="background-color: #e74c3c;">
                    <div>
                        <h3 class="text-uppercase" style="font-weight: 600;">What you get</h3>
                        <p style="line-height: 1.8;">FastSMS, is a one stop solution for your Marketing team. We provide Masking on all operators in Bangladesh. We provide dipping query facilities to find exact MNP(mobile number portability) users. We have all the possible channels to connect you and your customers.</p>
                        <i class="icon-thumbs-up bgicon"></i>
                    </div>
                </div>

                <div class="clear"></div>

            </div>


            <div class="container clearfix">

                <div id="side-navigation" class="row col-mb-50" data-plugin="tabs">

{{--                    <div class="col-md-6 col-lg-4">--}}
{{--                        <ul class="sidenav">--}}
{{--                            <li class="ui-tabs-active"><a href="#snav-content1"><i class="icon-screen"></i>General Features<i class="icon-chevron-right"></i></a></li>--}}
{{--                            <li><a href="#snav-content2"><i class="icon-magic"></i>SMS API Features<i class="icon-chevron-right"></i></a></li>--}}
{{--                            <li><a href="#snav-content3"><i class="icon-star3"></i>Wordpress Plugin Features<i class="icon-chevron-right"></i></a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}

                    <div class="col-md-6">
                        <div id="snav-content1">
                            <div class="heading-block">
                                <h3>General Features</h3>
                            </div>
                            <ul class="check_list">
                                <li>First Awarded Best Bangladeshi SMS Service Provider</li>
                                <li>Fast SMS Sending Speed, Multiple Backup Route</li>
                                <li>Send SMS Using Bangladeshi Number to get more reliability and trust</li>
                                <li>Easy Control Panel With Online SMS Purchasing System</li>
                                <li>Access Token, API tools</li>
                                <li>Single SMS, Bulk SMS, Group SMS, Dynamic SMS and a lots of features available</li>
                                <li>Expert Support over phone/ticket/live chat</li>
                                <li>Bangla SMS Supported, Both Unicode & Non Unicode SMS Supported</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div id="snav-content2">
                            <div class="heading-block">
                                <h3>SMS API Features</h3>
                            </div>
                            <ul class="check_list">
                                <li>You can send bulk sms from any software, website or application using our API.</li>
                                <li>Sending SMS From PHP, ASP, ASPX, Visual Studio, Android Studio sample code is available on our API Page</li>
                                <li>Both http & https supported SMS API</li>
                                <li>Simple Line by line output Response</li>
                                <li>Synchronous and Asynchronous SMS API</li>
                                <li>Dynamic Many to Many SMS Supported</li>
                                <li>POST & GET Method Supported HTTP SMS API</li>
                                <li>Bangla SMS Supported API</li>
                                <li>No additional charge for API</li>
                            </ul>
                        </div>

                    </div>

                </div>

            </div>

{{--            <div id="oc-clients" class="section bg-transparent mt-0 owl-carousel owl-carousel-full image-carousel footer-stick carousel-widget" data-margin="80" data-loop="true" data-nav="false" data-autoplay="5000" data-pagi="false" data-items-xs="2" data-items-sm="3" data-items-md="4" data-items-lg="5" data-items-xl="6">--}}

{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/1.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/2.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/3.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/4.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/5.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/6.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/7.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/8.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/9.png') }}" alt="Clients"></a></div>--}}
{{--                <div class="oc-item"><a href="#"><img src="{{ asset('assets/frontend/images/clients/10.png') }}" alt="Clients"></a></div>--}}

{{--            </div>--}}


        </div>
    </section><!-- #content end -->
@endsection
