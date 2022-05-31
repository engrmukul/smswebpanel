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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('emailconfig.route.list') }}">Email Route List</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('emailconfig.route.edit', $route->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control col-form-label" for="user">User</label>
                                        <div class="col-md-10 input-element {{ $errors->has('operator') ? 'has-error' : '' }}">
                                            <select id="user" class="form-control border-primary" name="user">
                                                @if($route->user_id)
                                                    <option value="{{ $route->user_id }}">{{ $route->user->name }}</option>
                                                @else
                                                    <option value="{{ $route->user_id }}">Select User Name</option>
                                                @endif
                                            </select>
                                            <span class="text-danger">{{ $errors->first('user') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control col-form-label" for="provider">Email Service Provider</label>
                                        <div class="col-md-10 input-element {{ $errors->has('provider') ? 'has-error' : '' }}">
                                            <select id="provider" class="form-control custom-select block border-primary" name="provider">
                                                <option value="">Select Email Provider</option>
                                                @foreach($providers as $provider)
                                                    <option value="{{ $provider->id }}" {{ ($route->email_service_provider_id == $provider->id)? 'selected': '' }}>{{ $provider->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('provider') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="source_domain">Source Domain</label>
                                        <div class="col-md-10 input-element {{ $errors->has('source_domain') ? 'has-error' : '' }}">
                                            <input type="text" id="source_domain" class="form-control border-primary" name="source_domain" value="{{ $route->source_domain }}">
                                            <span class="text-danger">{{ $errors->first('source_domain') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="cost">Cost</label>
                                        <div class="col-md-10 input-element {{ $errors->has('cost') ? 'has-error' : '' }}">
                                            <input type="text" id="cost" class="form-control border-primary" name="cost" value="{{ $route->cost }}">
                                            <span class="text-danger">{{ $errors->first('cost') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="success_rate">Success Rate</label>
                                        <div class="col-md-10 input-element {{ $errors->has('success_rate') ? 'has-error' : '' }}">
                                            <input type="text" id="success_rate" class="form-control border-primary" name="success_rate" value="{{ $route->success_rate }}">
                                            <span class="text-danger">{{ $errors->first('success_rate') }}</span>
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
                                                <a href="{{ route('emailconfig.route.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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

            $('#user').select2({
                ajax:{
                    url: '{{ route('select.user') }}',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return{
                            searchTerm : params.term,
                            _token : '{{ csrf_token() }}'
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            $('#provider').select2({
                placeholder: "Select Provider",
                allowClear: true
            });
        });
    </script>
@endsection
