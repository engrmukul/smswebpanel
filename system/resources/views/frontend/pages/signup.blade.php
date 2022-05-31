@extends('frontend.layouts.master')

@section('title')
    Sign Up
@endsection

@section('content')
    <!-- Content
            ============================================= -->
    <section id="content">
        <div class="content-wrap">
            <div class="container">
                @if(Session::has('message'))
                    <div class="style-msg {{ Session::get('m-class') }}">
                        <div class="sb-msg">{!! Session::get('message') !!}</div>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    </div>
                @endif
                <form class="form form-horizontal" method="post" action="{{ route('sign.up.post') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="fancy-title title-border title-center">
                            <h2><span style="text-decoration: underline;"><strong>Sign Up</strong></span></h2>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Name</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}">
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">User Name</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('username') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Username" name="username" value="{{ old('username') }}">
                                <span class="text-danger">{{ $errors->first('username') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Email</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Password</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('password') ? 'has-error' : '' }}">
                                <input type="password" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}">
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Confirm Password</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Mobile</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value="{{ old('mobile') }}">
                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Address</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('address') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Address" name="address" value="{{ old('address') }}">
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label" for="dipping">Dipping</label>
                            <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('dipping') ? 'has-error' : '' }}">
                                <select id="dipping" class="form-control custom-select block" name="dipping">
                                    <option value="Inactive" {{ (old('dipping') == 'Inactive')? 'selected': '' }}>No</option>
                                    <option value="Active" {{ (old('dipping') == 'Active')? 'selected': '' }}>Yes</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('dipping') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-md-10 pl-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-check-square-o"></i> Sign Up
                                </button>
                                <button type="button" class="btn btn-warning mr-1">
                                    <a href="{{ route('home') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section><!-- #content end -->
@endsection

@section('pageCSS')
    <style>
        label {
            font-size: 18px !important;
        }
    </style>
@endsection
