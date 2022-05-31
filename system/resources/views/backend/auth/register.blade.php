@extends('backend.layouts.auth')

@section('content')
    <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
        <div class="card-header border-0 pb-0">
            <div class="card-title text-center">
                <img src="assets/app-assets/images/logo/stack-logo-dark.png" alt="branding logo">
            </div>
        </div>
        <div class="card-content">
            <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1"><span>REGISTER</span></p>
            <div class="card-body pt-0">
                <form class="form-horizontal" action="">
                    <fieldset class="form-group floating-label-form-group">
                        <label for="user-name">User Name</label>
                        <input type="text" class="form-control" id="user-name" placeholder="User Name">
                    </fieldset>
                    <fieldset class="form-group floating-label-form-group">
                        <label for="user-email">Your Email Address</label>
                        <input type="email" class="form-control" id="user-email" placeholder="Your Email Address">
                    </fieldset>
                    <fieldset class="form-group floating-label-form-group mb-1">
                        <label for="user-password">Enter Password</label>
                        <input type="password" class="form-control" id="user-password" placeholder="Enter Password">
                    </fieldset>
                    <div class="form-group row">
                        <div class="col-sm-6 col-12 text-center text-sm-left pr-0">
                            <fieldset>
                                <input type="checkbox" id="remember-me" class="chk-remember">
                                <label for="remember-me"> Remember Me</label>
                            </fieldset>
                        </div>
                        <div class="col-sm-6 col-12 float-sm-left text-center text-sm-right">
                            <a href="#" class="card-link">Forgot Password?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-block"><i class="feather icon-user"></i> Register</button>
                </form>
            </div>
            <div class="card-body pt-0">
                <a href="{{ route('login') }}" class="btn btn-outline-danger btn-block"><i class="feather icon-unlock"></i> Login</a>
            </div>
        </div>
    </div>
@endsection
