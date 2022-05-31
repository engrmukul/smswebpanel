@extends($layout)
@section('pageVendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
@endsection

@section('pageCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/core/menu/menu-types/vertical-menu-modern.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/fonts/simple-line-icons/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/css/pages/timeline.css') }}">
@endsection

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
                            <table id="datatable" class="table table-striped table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th width="20">#</th>
                                    <th>Article Type</th>
                                    <th>Article Title</th>
                                    <th width="30">Manage</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($articles as $article)
                                    <tr>
                                        <td></td>
                                        <td>
                                            @if($article->article_type == '1')
                                                About Us
                                            @elseif($article->article_type == '2')
                                                Our Mission
                                            @else
                                                Policy
                                            @endif
                                        </td>
                                        <td>{{ $article->title }}</td>
                                        <td>
                                            <a href="{{ route('article.edit', $article->id) }}" class="btn-edit"><i class="fa fa-edit"></i></a>
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

@section('pageVendorJS')
    <script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
@endsection

@section('pageJS')
    <script src="{{ asset('assets/js/datatable-custom.js') }}"></script>
@endsection
