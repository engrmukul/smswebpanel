@extends($layout)

@section('title')
    {{ $title }}
@endsection

@section('breadcrumb')
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{ $title }}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <form class="form form-horizontal" method="post" action="{{ route('profile.edit.post', $user->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Name</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Name" name="name" value="{{ $user->name }}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Mobile</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value="{{ $user->mobile }}">
                                            <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Address</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('address') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Address" name="address" value="{{ $user->address }}">
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-2 pl-3 col-form-label">Profile Image</label>
                                        <div class="col-10 pl-3 pr-3 input-element {{ $errors->has('image') ? 'has-error' : '' }}">
                                            @if(Auth::user()->photo !=null && file_exists('assets/images/users/'.Auth::user()->photo))
                                                <img src="{{ asset('assets/images/users/'.Auth::user()->photo) }}" width="70" height="70">
                                            @else
                                                <img src="{{ asset('assets/app-assets/images/portrait/small/avatar-s-1.png') }}" width="70" height="70">
                                            @endif
                                            <input type="file" name="image" />
                                            <span class="text-danger">{{ $errors->first('image') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10 pl-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Edit Profile
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('dashboard') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <form class="form form-horizontal" method="post" action="{{ route('password.change.post', $user->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">Password Change</h4>

                                    <div class="form-group row">
                                        <label class="col-md-3 pl-3 col-form-label">Old Password</label>
                                        <div class="col-md-9 pl-3 pr-3 input-element {{ $errors->has('old_password') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Old Password" name="old_password">
                                            <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 pl-3 col-form-label">New Password</label>
                                        <div class="col-md-9 pl-3 pr-3 input-element {{ $errors->has('new_password') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="New Password" name="new_password">
                                            <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 pl-3 col-form-label">Confirm New Password</label>
                                        <div class="col-md-9 pl-3 pr-3 input-element {{ $errors->has('new_password_confirmation') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Confirm New Password" name="new_password_confirmation">
                                            <span class="text-danger">{{ $errors->first('new_password_confirmation') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-9 pl-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Change
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('dashboard') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageJS')
    <script>
        $(document).ready(function (){
            $('.select2').select2({
                width: '100%'
            });
        });

    </script>
@endsection
