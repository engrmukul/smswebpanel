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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('smsconfig.route.list') }}">Route List</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section id="horizontal-form-layouts" class="basic-select2">
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
                            <form class="form form-horizontal" method="post" action="{{ route('smsconfig.route.edit.post', $route->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12 col-form-label" for="user">User</label>
                                                <div class="col-md-12 input-element {{ $errors->has('operator') ? 'has-error' : '' }}">
                                                    <select id="user" class="form-control block border-primary" name="user">
                                                        @if($route->user_id)
                                                            <option value="{{ $route->user_id }}">{{ $route->user->name }}</option>
                                                        @else
                                                            <option value="{{ $route->user_id }}">Select User Name</option>
                                                        @endif
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('user') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12 col-form-label" for="operator">Operator</label>
                                                <div class="col-md-12 input-element {{ $errors->has('operator_prefix') ? 'has-error' : '' }}">
                                                    <select id="operator" class="select2 form-control border-primary" multiple="multiple" name="operator_prefix[]">
                                                        <option value="">All Operators</option>
                                                        @foreach($operators as $operator)
                                                            <option value="{{ $operator->prefix }}" {{in_array($operator->prefix, $route->operator_prefix ?: []) ? "selected" : ""}} >{{ $operator->full_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('operator_prefix') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12 col-form-label" for="channel">Channel <i class="fa fa-star danger"></i></label>
                                                <div class="col-md-12 input-element {{ $errors->has('channel') ? 'has-error' : '' }}">
                                                    <select id="channel" class="form-control custom-select block border-primary" name="channel">
                                                        <option value="">Select Channel</option>
                                                        @foreach($channels as $channel)
                                                            <option value="{{ $channel->id }}" {{ ($route->channel_id == $channel->id)? 'selected': '' }}>{{ $channel->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('channel') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12 col-form-label" for="has_mask">Has Mask <i class="fa fa-star danger"></i></label>
                                                <div class="col-md-12 input-element {{ $errors->has('has_mask') ? 'has-error' : '' }}">
                                                    <select id="has_mask" class="form-control custom-select block border-primary" name="has_mask">
                                                        <option value="">Select Option</option>
                                                        <option value="2" {{ ($route->has_mask == 2)? 'selected': '' }}>ALL</option>
                                                        <option value="1" {{ ($route->has_mask == 1)? 'selected': '' }}>YES</option>
                                                        <option value="0" {{ ($route->has_mask == 0)? 'selected': '' }}>NO</option>
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('has_mask') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12 col-form-label" for="default_mask">Default Mask</label>
                                                <div class="col-md-12 input-element {{ $errors->has('default_mask') ? 'has-error' : '' }}">
                                                    <input type="text" id="default_mask" class="form-control border-primary" name="default_mask" value="{{ $route->default_mask }}">
                                                    <span class="text-danger">{{ $errors->first('default_mask') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12 col-form-label" for="cost">Cost</label>
                                                <div class="col-md-12 input-element {{ $errors->has('cost') ? 'has-error' : '' }}">
                                                    <input type="text" id="cost" class="form-control border-primary" name="cost" value="{{ $route->cost }}">
                                                    <span class="text-danger">{{ $errors->first('cost') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-12 col-form-label" for="success_rate">Success Rate</label>
                                                <div class="col-md-12 input-element {{ $errors->has('success_rate') ? 'has-error' : '' }}">
                                                    <input type="text" id="success_rate" class="form-control border-primary" name="success_rate" value="{{ $route->success_rate }}">
                                                    <span class="text-danger">{{ $errors->first('success_rate') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Update
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('smsconfig.route.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
        $(document).ready(function () {

            $('#user').select2({
                width: '100%',
                ajax: {
                    url: '{{ route('select.user') }}',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            _token: '{{ csrf_token() }}'
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

            $('#operator').select2({
                placeholder: "ALL Operators",
                multiple: true,
                allowClear: true,
                width: '100%'
            });

        });
    </script>
@endsection

