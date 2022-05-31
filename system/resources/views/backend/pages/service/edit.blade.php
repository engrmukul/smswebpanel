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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('service.list') }}">Services</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('service.edit', $service->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>
                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Service Title</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('title') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Service Title" name="title" value="{{ $service->title }}">
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Description</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('description') ? 'has-error' : '' }}">
                                            <textarea name="description" id="service_ckeditor">{{ $service->description }}</textarea>
                                            <span class="text-danger">{{ $errors->first('description') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Images</label>
                                        <div class="col-md-10 pr-3 input-element {{ $errors->has('image') ? 'has-error' : '' }}">
                                            <div class="col-12 pl-1 row">
                                                <div>
                                                    <span class="btn btn-success fileinput-button">
                                                        <span>Select Attachment</span>
                                                        <input type="file" name="image" id="files" multiple accept="image/jpeg, image/png, image/gif,"><br/>
                                                    </span>
                                                </div>

                                                <ul class="thumb-Images">
                                                    <li>
                                                        <img class="thumb" src="{{ asset('assets/images/services/'.$service->image) }}" height="70" width="70">
                                                        <div>Old Image</div>
                                                    </li>
                                                </ul>

                                            </div>
                                            <br><br>
                                            <div class="col-12 pl-0">
                                                <output id="Filelist" class="pl-0"></output>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('image') }}</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Save
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('service.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
    <script src="{{ asset('assets/app-assets/vendors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/single-file-upload.js') }}"></script>
    <script>
        CKEDITOR.replace('service_ckeditor');
    </script>
@endsection
