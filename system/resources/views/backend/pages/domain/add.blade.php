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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('config.domain.list') }}">Domain List</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('config.domain.add.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row" id="contactName">
                                        <label class="col-md-2 col-form-label label-control" for="name">Domain Name <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input type="text" id="name" class="form-control border-primary" placeholder="Domain Name" name="name" value="{{ old('name') }}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control">Reseller Name</label>
                                        <div class="col-md-10 input-element {{ $errors->has('reseller_name') ? 'has-error' : '' }}">
                                            <select class="select2 form-control block border-primary" name="reseller_name">
                                                <option value="">Select Reseller</option>
                                                @foreach($resellers as $reseller)
                                                    <option value="{{ $reseller->id }}" {{ (old('reseller_name') == $reseller->id)? 'selected': '' }}>{{ $reseller->reseller_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('reseller_name') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-contact mb-0 justify-content-end row">
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Add
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('config.domain.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
