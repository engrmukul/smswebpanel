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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('service.add') }}">Add Service</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section id="configuration">
        <div class="row">
            <div class="col-12">
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
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <table id="datatable" class="table table-striped table-sm">
                                <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Service Title</th>
                                    <th>Service Slug</th>
                                    <th>Service Image</th>
                                    <th>Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td style="vertical-align: middle"></td>
                                        <td style="vertical-align: middle">{{ $service->title }}</td>
                                        <td style="vertical-align: middle">{{ $service->slug }}</td>
                                        <td style="vertical-align: middle"><img width="100px" height="50px" src="{{ asset("assets/images/services/".$service->image) }}"></td>
                                        <td style="vertical-align: middle">
                                            <a href="{{ route('service.edit', $service->id) }}" class="btn-edit"><i class="fa fa-edit"></i></a>
                                            <a href="" onclick="return myDeleteFunction('{{ $service->title }}', '{{ route('service.delete', $service->id) }}', 'Service')" class="btn-del">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                            <form id="delete" action="" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageJS')
    <script src="{{ asset('assets/js/datatable-custom.js') }}"></script>
@endsection
