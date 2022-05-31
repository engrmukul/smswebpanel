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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('smsconfig.channel.list') }}">User List</a>
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
                                        <span class="d-md-block d-none">Edit Channel</span>
                                    </button>
                                </div>
                            </div>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%" class="text-right">Channel Name :</th>
                                    <td>{{ $channel->name }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Operators:</th>
                                    <td>{{ is_array($data->operator_prefix)? implode(',', $data->operator_prefix): '' }}</td>
                                </tr>
                                @if ($channel->channel_type == 'HTTP')
                                    <tr>
                                        <th class="text-right">URL :</th>
                                        <td>{!! $channel->url !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Method :</th>
                                        <td>{!! $channel->method !!}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="text-right">IP :</th>
                                        <td>{!! $channel->ip !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Port :</th>
                                        <td>{!! $channel->port !!}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="text-right">Username :</th>
                                    <td>{!! $channel->username !!}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Password :</th>
                                    <td>{{ $channel->password }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Mode :</th>
                                    <td>{{ $channel->mode }}</td>
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
            var route = '{{ route('smsconfig.channel.edit', $channel->id) }}';
            window.history.pushState('', '', route);
            $.get(route).done(function(page_content){
                $('.app-content').html(page_content);
            }).fail(function (data, textStatus, xhr) {
                window.location.href = "/smspanel/dashboard";
            });
        });
    </script>
@endsection
