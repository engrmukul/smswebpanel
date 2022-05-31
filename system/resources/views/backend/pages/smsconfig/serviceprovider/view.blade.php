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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('smsconfig.serviceprovider.list') }}">User List</a>
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
                                <li><a class="ajax-loading" href="{{ route('smsconfig.serviceprovider.edit', $provider->id) }}" ><i class="feather icon-edit"></i></a></li>
                                <li><a class="ajax-loading"  href="{{ route('smsconfig.serviceprovider.list') }}"><i class="feather icon-list"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%" class="text-right">SMS Provider Name :</th>
                                    <td>{{ $provider->name }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">API Provider :</th>
                                    <td>{{ $provider->api_provider }}</td>
                                </tr>
                                @if ($provider->channel_type == 'HTTP')
                                    <tr>
                                        <th class="text-right">URL :</th>
                                        <td>{!! $provider->url !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Method :</th>
                                        <td>{!! $provider->method !!}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="text-right">IP :</th>
                                        <td>{!! $provider->ip !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Port :</th>
                                        <td>{!! $provider->port !!}</td>
                                    </tr>
                                @endif
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
                                <tr>
                                    <th width="20%" class="text-right">Mode :</th>
                                    <td>{{ $provider->mode }}</td>
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
            var route = '{{ route('smsconfig.serviceprovider.edit', $provider->id) }}';
            window.history.pushState('', '', route);
            $.get(route).done(function(page_content){
                $('.app-content').html(page_content);
            }).fail(function (data, textStatus, xhr) {
                window.location.href = "/smspanel/dashboard";
            });
        });
    </script>
@endsection
