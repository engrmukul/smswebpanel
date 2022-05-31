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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('emailconfig.serviceprovider.list') }}">Email Provider List</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('emailconfig.serviceprovider.edit.post', $provider->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">Edit Email Service Provider</h4>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="name">Service Provider Name <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input type="text" id="name" class="form-control border-primary" placeholder="Service Provider Name" name="name" value="{{ $provider->name }}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="api_provider">API Provider <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input type="text" id="name" class="form-control border-primary" placeholder="API Provider Name" name="api_provider" value="{{ $provider->api_provider}}">
                                            <span class="text-danger">{{ $errors->first('api_provider') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control col-form-label" for="provider_type">Service Provider Type <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('provider_type') ? 'has-error' : '' }}">
                                            <select id="provider_type" class="form-control custom-select block border-primary" name="provider_type">
                                                <option value="">Select Type</option>
                                                <option value="HTTP" {{ ($provider->provider_type == 'HTTP')? 'selected': '' }}>HTTP</option>
                                                <option value="SMTP" {{ ($provider->provider_type == 'SMTP')? 'selected': '' }}>SMTP</option>
                                                <option value="SDK" {{ ($provider->provider_type == 'SDK')? 'selected': '' }}>SDK</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('provider_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="url">Email Provider URL</label>
                                        <div class="col-md-10 input-element {{ $errors->has('url') ? 'has-error' : '' }}">
                                            <input type="text" id="url" class="form-control border-primary" placeholder="Email Provider URL" name="url" value="{{ $provider->url}}">
                                            <span class="text-danger">{{ $errors->first('url') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="port">Port</label>
                                        <div class="col-md-10 input-element {{ $errors->has('port') ? 'has-error' : '' }}">
                                            <input type="text" id="port" class="form-control border-primary" name="port" value="{{ $provider->port}}">
                                            <span class="text-danger">{{ $errors->first('port') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control col-form-label" for="tls">TLS</label>
                                        <div class="col-md-10 input-element {{ $errors->has('tls') ? 'has-error' : '' }}">
                                            <select id="tls" class="form-control custom-select block border-primary" name="tls">
                                                <option value="">Select TLS</option>
                                                <option value="Yes" {{ ($provider->tls == 'Yes')? 'selected': '' }}>Yes</option>
                                                <option value="No" {{ ($provider->tls == 'No')? 'selected': '' }}>No</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('tls') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="api_key">API KEY</label>
                                        <div class="col-md-10 input-element {{ $errors->has('api_key') ? 'has-error' : '' }}">
                                            <input type="text" id="api_key" class="form-control border-primary" name="api_key" value="{{ $provider->api_key}}">
                                            <span class="text-danger">{{ $errors->first('api_key') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="secret_key">SECRET KEY</label>
                                        <div class="col-md-10 input-element {{ $errors->has('secret_key') ? 'has-error' : '' }}">
                                            <input type="text" id="secret_key" class="form-control border-primary" name="secret_key" value="{{ $provider->secret_key}}">
                                            <span class="text-danger">{{ $errors->first('secret_key') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="username">Username</label>
                                        <div class="col-md-10 input-element {{ $errors->has('username') ? 'has-error' : '' }}">
                                            <input type="text" id="username" class="form-control border-primary" name="username" value="{{ $provider->username}}">
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="password">Password</label>
                                        <div class="col-md-10 input-element {{ $errors->has('password') ? 'has-error' : '' }}">
                                            <input type="text" id="password" class="form-control border-primary" name="password" value="{{ $provider->password}}">
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="tps">TPS</label>
                                        <div class="col-md-10 input-element {{ $errors->has('tps') ? 'has-error' : '' }}">
                                            <input type="text" id="tps" class="form-control border-primary" name="tps" value="{{ $provider->tps}}">
                                            <span class="text-danger">{{ $errors->first('tps') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Add
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('emailconfig.serviceprovider.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
            $('#channel_type').change(function (){
                let type = $(this).val();
                if (type === 'HTTP'){
                    $('#methodHide').show();
                    $('#urlHide').show();
                    $('#ipHide').hide();
                    $('#portHide').hide();
                    $('#ip').val('');
                    $('#port').val('');
                } else if (type === 'SMPP') {
                    $('#methodHide').hide();
                    $('#urlHide').hide();
                    $('#ipHide').show();
                    $('#portHide').show();
                    $('#url').val('');
                    $('#method').prop("selectedIndex", 0);
                } else {
                    $('#methodHide').hide();
                    $('#urlHide').hide();
                    $('#ipHide').hide();
                    $('#portHide').hide();
                    $('#method').prop("selectedIndex", 0);
                    $('#url').val('');
                    $('#username').val('');
                    $('#password').val('');
                }
            });
            $('#method').change(function () {
                let method_name = $(this).val();
                if (method_name === 'POST') {
                    $('#contentHide').show();
                } else {
                    $('#contentHide').hide();
                    $('#content_type').prop("selectedIndex", 0);
                }
            });
        });
    </script>
@endsection
