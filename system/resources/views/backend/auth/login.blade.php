@extends('backend.layouts.auth')

@section('content')
    <div class="card border-grey border-lighten-3 m-0">
        <div class="card-header border-0">
            <div class="card-title text-center">
                <div class="p-1">
                    <h2 class="brand-text">
                        @if(!empty($config->logo) && file_exists("assets/images/".$config->logo))
                            <img class="brand-logo" alt="stack admin logo"
                                 src="{{ asset("assets/images/".$config->logo) }}">
                        @else
                            <img class="brand-logo" alt="stack admin logo"
                                 src="/assets/app-assets/images/logo/stack-logo.png">
                        @endif
                        {{ $config->brand_name }}
                    </h2>
                </div>
            </div>
            <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2"><span>Panel Login</span></h6>
        </div>

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
        <div class="card-content">
            <div class="card-body pt-0">
                <form class="form-horizontal" id="loginForm" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <fieldset class="form-group floating-label-form-group input-element {{ $errors->has('email') || $errors->has('username') || $errors->has('login') ? ' has-error' : '' }}">
                        <label for="user-name">Your Username Or Email</label>
                        <input type="text" class="form-control" id="user-name" name="login" placeholder="Your Username Or Email" VALUE="{{ old('username') ?: old('email') }}">
                        @if ($errors->has('email') || $errors->has('username') || $errors->has('login'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') ?: ($errors->first('username')?: $errors->first('login')) }}</strong>
                            </span>
                        @endif
                    </fieldset>
                    <fieldset class="form-group floating-label-form-group mb-1 input-element {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="user-password">Enter Password</label>
                        <input type="password" class="form-control" id="user-password" name="password" placeholder="Enter Password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </fieldset>
                    <div class="form-group row">
                        <div class="col-sm-6 col-12 text-center text-sm-left">
                            <fieldset>
                                <input type="checkbox" id="remember-me" name="remember" class="chk-remember">
                                <label for="remember-me"> Remember Me</label>
                            </fieldset>
                        </div>
                        <div class="col-sm-6 col-12 float-sm-left text-center text-sm-right">
                            <a href="{{ route('password.request') }}" class="card-link">Forgot Password?</a>
                        </div>
                    </div>
                    <button type="submit" id="submit" class="btn btn-outline-primary btn-block"><i class="feather icon-unlock"></i> Login</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notify modal -->
    <div class="modal text-left" id="notify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title text-bold-700 danger" id="myModalLabel33">Important Notification / বিজ্ঞপ্তি</label>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p><strong>
                            ১। বিটিআরসি প্রদত্ত বিজ্ঞপ্তি অনুযায়ী যে কোনো পণ্য অথবা সেবা বিষয়ক সতর্কতামূলক, নির্দেশনা, প্রচার ও প্রচারণামূলক এসএমএস বাংলা ভাষায় পাঠানোর জন্য কঠোরভাবে নির্দেশনা দেয়া হয়েছে।</strong></p>
                    <p><strong>২। আমরা বিশ্বাস করি আপনি এবং আপনার প্রতিষ্ঠান আইনের প্রতি শ্রদ্ধাশীল থাকবেন এবং অনতিবিলম্বে এটি কার্যকর করবেন।</strong></p>
                    <p><strong>উপরোক্ত নির্দেশনা সম্পর্কে আরো বিস্তারিত তথ্য জানতে যোগাযোগ করুন আপনার সেবায় নিযুক্ত আমাদের বিক্রয় প্রতিনিধির সাথে অথবা সাপোর্ট সেন্টারে।
                        </strong></p>

                    <p><strong> <b>Dear Concern,</b></strong></p>
                    <p><strong>As per the circular of BTRC it has been instructed to all public and private organizations to send any kind of SMS notifications/ alerts/ promotions in Bangla language to the Mobile Subscribers.</strong></p>
                    <p><strong>We believe that you and your company will be respectful to laws of the land and comply with this immediately.</strong></p>


                    <p><a href="/assets/example/BTRC_Letter_Regarding_SMS_Circulation.pdf" target="_blank">BTRC Latter</a></p>
                </div>

                <div class="modal-footer">
                    <input type="reset" class="btn btn-outline-secondary btn-lg" onclick="submitForm();" data-dismiss="modal" value="close">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
@endsection

@section('pageJS')
    <script src="{{ asset('assets/app-assets/js/scripts/modal/components-modal.js') }}"></script>
    <script>

        $(document).ready(function (){
            var modal = document.getElementById('notify');
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
                $('#loginForm').submit();
            }

            // $('#submit').click(function (){
            //     $('#notify').modal('show');
            //     return false;
            // });
        });
        function submitForm() {
            $('#loginForm').action = '{{ route('login') }}';
            alert($('#loginForm').getAction());
            $('#loginForm').submit();
        }

    </script>
@endsection
