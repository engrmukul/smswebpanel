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
                        <h4 class="card-title">{{ $title }}</h4>
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
                            <div class="form-group justify-content-end row">
                                <div class="col-md-3 mb-1 justify-content-end row mr-1">
                                    <button class="btn btn-primary btn-md submit"><i class="d-md-none d-block feather icon-plus"></i>
                                        <span class="d-md-block d-none">Edit Email Provider</span>
                                    </button>
                                </div>
                            </div>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%" class="text-right">Provicer Name :</th>
                                    <td>{{ $provider->name }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">API Provider :</th>
                                    <td>{{ $provider->api_provider }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Provider Type :</th>
                                    <td>{{ $provider->provider_type }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Provider Url :</th>
                                    <td>{{ $provider->url }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Provider Port:</th>
                                    <td>{{ $provider->port }}</td>
                                </tr>

                                <tr>
                                    <th width="20%" class="text-right">Provider TLS:</th>
                                    <td>{{ $provider->tls }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Username :</th>
                                    <td>{!! $provider->username !!}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Password :</th>
                                    <td>{{ $provider->password }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">TPS :</th>
                                    <td>{{ $provider->tps }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('pageJS')
    <script>
        $(document).on("click", ".submit", function () {
            var route = '{{ route('emailconfig.serviceprovider.edit', $provider->id) }}';
            window.history.pushState('', '', route);
            $.get(route).done(function(page_content){
                $('.app-content').html(page_content);
            }).fail(function (data, textStatus, xhr) {
                window.location.href = "/smspanel/dashboard";
            });
        });
    </script>
@endsection
