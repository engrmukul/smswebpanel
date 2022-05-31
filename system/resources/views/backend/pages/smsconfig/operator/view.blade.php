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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('smsconfig.operator.list') }}">Operator List</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('smsconfig.operator.edit.post', $operator->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="full_name">Operator Name <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('full_name') ? 'has-error' : '' }}">
                                            <input type="text" id="full_name" class="form-control border-primary" placeholder="Operator Name" name="full_name" value="{{ $operator->full_name }}">
                                            <span class="text-danger">{{ $errors->first('full_name') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="short_name">Short Name <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('short_name') ? 'has-error' : '' }}">
                                            <input type="text" id="short_name" class="form-control border-primary" placeholder="Short Name" name="short_name" value="{{ $operator->short_name }}">
                                            <span class="text-danger">{{ $errors->first('short_name') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="prefix">Prefix <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('prefix') ? 'has-error' : '' }}">
                                            <input type="text" id="prefix" class="form-control border-primary" placeholder="Prefix" name="prefix" value="{{ $operator->prefix }}">
                                            <span class="text-danger">{{ $errors->first('prefix') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control col-form-label" for="country">Country <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('country') ? 'has-error' : '' }}">
                                            <select id="country" class="form-control custom-select block border-primary" name="country">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ ($operator->country_id == $country->id)? 'selected': '' }}>{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('country') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="ton">TON</label>
                                        <div class="col-md-10 input-element {{ $errors->has('ton') ? 'has-error' : '' }}">
                                            <input type="text" id="ton" class="form-control border-primary" placeholder="TON" name="ton" value="{{ $operator->ton }}">
                                            <span class="text-danger">{{ $errors->first('prefix') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="npi">NPI</label>
                                        <div class="col-md-10 input-element {{ $errors->has('npi') ? 'has-error' : '' }}">
                                            <input type="text" id="npi" class="form-control border-primary" placeholder="NPI" name="npi" value="{{ $operator->npi }}">
                                            <span class="text-danger">{{ $errors->first('npi') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Update
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('smsconfig.operator.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
