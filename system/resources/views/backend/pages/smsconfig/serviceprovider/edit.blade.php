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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('smsconfig.serviceprovider.list') }}">SMS Provider List</a>
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
                            <li><a class="ajax-loading"  href="{{ route('smsconfig.serviceprovider.list') }}"><i class="feather icon-list"></i></a></li>
                        </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <form class="form form-horizontal" method="post" action="{{ route('smsconfig.serviceprovider.edit.post', $provider->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">Edit SMS Provider</h4>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="name">SMS Provider Name <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input type="text" id="name" class="form-control border-primary" placeholder="SMS Provider Name" name="name" value="{{ $provider->name }}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="name">API Provider <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('api_provider') ? 'has-error' : '' }}">
                                            <input type="text" id="name" class="form-control border-primary" placeholder="API provider identifier" name="api_provider" value="{{ $provider->api_provider }}">
                                            <span class="text-danger">{{ $errors->first('api_provider') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control col-form-label" for="channel_type">SMS Provider Type <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('channel_type') ? 'has-error' : '' }}">
                                            <select id="channel_type" class="form-control custom-select block border-primary" name="channel_type">
                                                <option value="">Select Type</option>
                                                <option value="HTTP" {{ ($provider->channel_type == 'HTTP')? 'selected': '' }}>HTTP</option>
                                                <option value="SMPP" {{ ($provider->channel_type == 'SMPP')? 'selected': '' }}>SMPP</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('channel_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="methodHide" style="display: {{ ($errors->has('method') or $provider->method != '') ? '' : 'none' }}">
                                        <label class="col-md-2 label-control col-form-label" for="method">Method <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('method') ? 'has-error' : '' }}">
                                            <select id="method" class="form-control custom-select block border-primary" name="method">
                                                <option value="">Select Method</option>
                                                <option value="GET" {{ ($provider->method == 'GET')? 'selected': '' }}>GET</option>
                                                <option value="POST" {{ ($provider->method == 'POST')? 'selected': '' }}>POST</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('method') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="contentHide" style="display: {{ ($errors->has('content_type') or $provider->method == 'POST')  ? '' : 'none' }}">
                                        <label class="col-md-2 col-form-label label-control" for="content_type">Content Type <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('url') ? 'has-error' : '' }}">
                                            <select id="content_type" class="form-control custom-select block border-primary" name="content_type">
                                                <option value="">Select Content Type</option>
                                                <option value="ARRAY" {{ ($provider->content_type == 'ARRAY')? 'selected': '' }}>ARRAY</option>
                                                <option value="JSON" {{ ($provider->content_type == 'JSON')? 'selected': '' }}>JSON</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('content_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="urlHide" style="display: {{ ($errors->has('url') or $provider->channel_type == 'HTTP')  ? '' : 'none' }}">
                                        <label class="col-md-2 col-form-label label-control" for="url">Send SMS Base URL <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('url') ? 'has-error' : '' }}">
                                            <input type="text" id="url" class="form-control border-primary" placeholder="Send SMS Base URL" name="url" value="{{ $provider->url }}">
                                            <span class="text-danger">{{ $errors->first('url') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="ipHide" style="display: {{ ($errors->has('ip') or $provider->ip != '') ? '' : 'none' }}">
                                        <label class="col-md-2 col-form-label label-control" for="ip">IP <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('ip') ? 'has-error' : '' }}">
                                            <input type="text" id="ip" class="form-control border-primary" name="ip" value="{{ $provider->ip }}">
                                            <span class="text-danger">{{ $errors->first('ip') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="portHide" style="display: {{ ($errors->has('port') or $provider->port != '') ? '' : 'none' }}">
                                        <label class="col-md-2 col-form-label label-control" for="port">Port <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('port') ? 'has-error' : '' }}">
                                            <input type="text" id="port" class="form-control border-primary" name="port" value="{{ $provider->port }}">
                                            <span class="text-danger">{{ $errors->first('port') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="account-code">Account Code</label>
                                        <div class="col-md-10 input-element {{ $errors->has('account_code') ? 'has-error' : '' }}">
                                            <input type="text" id="account-code" class="form-control border-primary" name="account_code" value="{{ $provider->account_code }}">
                                            <span class="text-danger">{{ $errors->first('account_code') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="username">Username <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('username') ? 'has-error' : '' }}">
                                            <input type="text" id="username" class="form-control border-primary" name="username" value="{{ $provider->username }}">
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="password">Password <i class="fa fa-star danger"></i></label>
                                        <div class="col-md-10 input-element {{ $errors->has('password') ? 'has-error' : '' }}">
                                            <input type="text" id="password" class="form-control border-primary" name="password" value="{{ $provider->password }}">
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="tps">TPS</label>
                                        <div class="col-md-10 input-element {{ $errors->has('tps') ? 'has-error' : '' }}">
                                            <input type="text" id="tps" class="form-control border-primary" name="tps" value="{{ $provider->tps }}">
                                            <span class="text-danger">{{ $errors->first('tps') }}</span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="mode">Mode</label>
                                        <div class="col-md-10 input-element {{ $errors->has('mode') ? 'has-error' : '' }}">
                                            <input type="text" id="mode" class="form-control border-primary" name="mode" value="{{ $provider->mode }}">
                                            <span class="text-danger">{{ $errors->first('mode') }}</span>
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
                                                <a href="{{ route('smsconfig.serviceprovider.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
