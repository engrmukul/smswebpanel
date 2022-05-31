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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('balance.transfer.list.reseller') }}">Reseller Transfer List</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('balance.add.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label label-control">User</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('user') ? 'has-error' : '' }}">
                                            <select class="select2 form-control block" name="reseller" required>
                                                <option value="">Select Reseller</option>
                                            @foreach($resellers as $reseller)
                                                <option value="{{ $reseller->id }}" {{ (old('reseller') == $reseller->id)? 'selected': '' }}>{{ $reseller->reseller_name }}</option>
                                            @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('reseller') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label label-control" for="amount">Amount</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('amount') ? 'has-error' : '' }}">
                                            <input type="text" id="amount" class="form-control" placeholder="Amount" name="amount" value="{{ old('amount')? old('amount'):0 }}">
                                            <span class="text-danger">{{ $errors->first('amount') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label label-control" for="comment">Comment</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('comment') ? 'has-error' : '' }}">
                                            <input type="text" id="comment" class="form-control" placeholder="Comment" name="comment" value="{{ old('comment') }}">
                                            <span class="text-danger">{{ $errors->first('comment') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10 pl-3">
                                            <button type="submit" class="btn btn-primary generalFormSubmit">
                                                <code class="btn btn-sm d-none generalFormSubmitSpinner"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</code>
                                                <i class="fa fa-check-square-o generalFormSubmitButtonText"></i> Add
                                            </button>


                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('balance.transfer.list.reseller') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
