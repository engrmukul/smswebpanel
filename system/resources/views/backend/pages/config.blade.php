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
            <div class="col-md-12">
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
                            <form class="form form-horizontal" method="post" action="{{ route('setting.store') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $config->id }}">
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>
                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Brand Name</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('brand_name') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Brand Name" name="brand_name" value="{{ $config->brand_name }}">
                                            <span class="text-danger">{{ $errors->first('brand_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Logo</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('logo') ? 'has-error' : '' }}">
                                            <div class="col-12 pl-1 row">
                                                <div>
                                                    <span class="btn btn-success fileinput-button">
                                                        <span>Select Logo</span>
                                                        <input type="file" name="logo" id="files" accept="image/jpeg, image/png, image/jpg,"><br/>
                                                    </span>
                                                </div>

                                                @if ($config->logo)
                                                <ul class="thumb-Images">
                                                    <li>
                                                        <img class="thumb" src="{{ asset('assets/images/'.$config->logo) }}" height="70" width="70">
                                                        <div>Old Logo</div>
                                                    </li>
                                                </ul>
                                                @endif
                                            </div>

                                            <br><br>
                                            <div class="col-12 pl-0">
                                                <output id="Filelist" class="pl-0"></output>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('logo') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Address</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('address') ? 'has-error' : '' }}">
                                            <textarea name="address" class="form-control">{{ $config->address }}</textarea>
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Thana</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('thana') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Thana Name" name="thana" value="{{ $config->thana }}">
                                            <span class="text-danger">{{ $errors->first('thana') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">District</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('district') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="District Name" name="district" value="{{ $config->district }}">
                                            <span class="text-danger">{{ $errors->first('district') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Phone</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('phone') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Phone" name="phone" value="{{ $config->phone }}">
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Email</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ $config->email }}">
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Save
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
    <script src="{{ asset('assets/js/single-file-upload.js') }}"></script>
@endsection
