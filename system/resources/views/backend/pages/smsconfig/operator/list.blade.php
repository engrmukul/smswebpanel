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
                        <button link="{{ route('smsconfig.operator.add') }}" class="btn btn-primary btn-md submit"><i class="d-md-none d-block feather icon-plus"></i>
                            <span class="d-md-block d-none">Add Operator</span>
                        </button>
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
                            @include('backend.pages.elements.tableboard')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageJS')
    @include('backend.layouts.datatable-dynamic')
    <script src="{{ asset('assets/js/statusmenu.js') }}"></script>
    <script>
        $(document).ready(function ($) {
            var menuItems=[
                {title:'<i class="fa fa-check-circle text-success"> Active</i>',value:'Active'},
                {title:'<i class="fa fa-times-circle text-danger"> Inactive</i>',value:'Inactive'}
            ];

            $(document).on("click", "#status", function () {
                $('span#status').statusMenu({items: menuItems});
            });
        });

        $(document).on("click", "#change_status_menu", function () {
            var route = '{{ route('smsconfig.operator.status', ':id') }}';
            var status_id = $(this).data('id');
            var status_value = $(this).data('value');
            route = route.replace(':id', status_id);
            // var parent = $(this).parent().parent();
            $.ajax(
                {
                    type: "POST",
                    url: route,
                    data: {status: status_value, _token: '{{ csrf_token() }}'},
                    cache: false,
                    success: function (data) {
                        var obj = JSON.parse(data);
                        $('.dropdown-menu').hide();
                        $('.status_change_id_' + obj.id).html(obj.data);
                    }
                });
            return false;
        });
    </script>
@endsection
