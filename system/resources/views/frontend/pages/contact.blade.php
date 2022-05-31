@extends('frontend.layouts.master')

@section('title')
    Contact Us
@endsection

@section('content')
    <!-- Content
            ============================================= -->
    <section id="content">
        <div class="content-wrap">
            <div class="container">

                <div class="row gutter-40 col-mb-80">
                    <!-- Postcontent
                    ============================================= -->
                    <div class="postcontent col-lg-9">

                        <h3>Contact Us</h3>



                            <div class="form-result"></div>

                            @if(Session::has('message'))
                                <div class="style-msg {{ Session::get('m-class') }}">
                                    <div class="sb-msg">{!! Session::get('message') !!}</div>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                </div>
                            @endif

                            <form class="mb-0" action="{{ route('contact.us.post') }}" method="post">

                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="template-contactform-name">Name <small>*</small></label>
                                        <input type="text" id="template-contactform-name" name="name" value="" class="sm-form-control required" />
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="template-contactform-email">Email <small>*</small></label>
                                        <input type="email" id="template-contactform-email" name="email" value="" class="required email sm-form-control" />
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="template-contactform-phone">Phone <small>*</small></label>
                                        <input type="text" id="template-contactform-phone" name="phone_number" value="" class="required sm-form-control" />
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-md-12 form-group">
                                        <label for="template-contactform-subject">Subject <small>*</small></label>
                                        <input type="text" id="template-contactform-subject" name="subject" value="" class="required sm-form-control" />
                                    </div>

                                    <div class="w-100"></div>

                                    <div class="col-12 form-group">
                                        <label for="template-contactform-message">Message <small>*</small></label>
                                        <textarea class="required sm-form-control" id="template-contactform-message" name="message" rows="6" cols="30"></textarea>
                                    </div>

                                    <div class="col-12 form-group d-none">
                                        <input type="text" id="template-contactform-botcheck" name="template-contactform-botcheck" value="" class="sm-form-control" />
                                    </div>

                                    <div class="col-12 form-group">
                                        <button class="button button-3d m-0" type="submit" name="template-contactform-submit" value="submit">Send Message</button>
                                    </div>
                                </div>

                            </form>

                    </div><!-- .postcontent end -->

                    <!-- Sidebar
                    ============================================= -->
                    <div class="sidebar col-lg-3">

                        <address>
                            <strong>Headquarters:</strong><br>
                            {{ $config->address }}<br>
                            {{ $config->thana }}, {{ $config->district }}<br>
                        </address>
                        <abbr title="Phone Number"><strong>Phone:</strong></abbr> {{ $config->phone }}<br>
                        <abbr title="Email Address"><strong>Email:</strong></abbr> {{ $config->email }}

                        <div class="widget border-0 pt-0">

                            <a href="https://www.facebook.com/fastsmsportal" class="social-icon si-small si-dark si-facebook">
                                <i class="icon-facebook"></i>
                                <i class="icon-facebook"></i>
                            </a>

                            <a href="#" class="social-icon si-small si-dark si-twitter">
                                <i class="icon-twitter"></i>
                                <i class="icon-twitter"></i>
                            </a>

                        </div>

                    </div><!-- .sidebar end -->
                </div>

            </div>
        </div>
    </section><!-- #content end -->
@endsection
