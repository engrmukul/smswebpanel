@extends('backend.layouts.auth')

@section('content')
    <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
        <div class="card-header border-0 pb-0">
            <div class="card-title text-center">
                <img class="brand-logo" alt="stack admin logo"
                     src="{{ asset("assets/images/".$config->logo) }}">
            </div>
            <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                <span>We will send you a link to reset password.</span>
            </h6>
        </div>
        <div class="card-content">
            <div class="card-body">
                @if (Session::has('message'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <form class="form-horizontal" action="{{ route('forget.password.post') }}"  method="POST" novalidate>
                    @csrf
                    <fieldset class="form-group position-relative has-icon-left">
                        <input type="email" name="email" class="form-control form-control-lg" id="user-email" placeholder="Your Email Address" required>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                        <div class="form-control-position">
                            <i class="feather icon-mail"></i>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-outline-primary btn-lg btn-block"><i class="feather icon-unlock"></i> Recover Password</button>
                </form>
            </div>
        </div>
        <div class="card-footer border-0">
            <p class="float-sm-left text-center"><a href="{{ route('login') }}" class="card-link">Login</a></p>
        </div>
    </div>
@endsection
