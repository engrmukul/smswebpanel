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
                        @if(empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4)
                        <button link="{{route('balance.add.reseller')}}" class="btn btn-primary btn-md submit" id="reseller_submit"><i class="d-md-none d-block feather icon-plus"></i>
                            <span class="d-md-block d-none">Add Reseller Balance</span>
                        </button>
                        @endif
                        @if(Auth::user()->id_user_group != 4)
                        <button link="{{route('balance.add.user') }}"  class="btn btn-primary btn-md submit" id="user_submit"><i class="d-md-none d-block feather icon-plus"></i>
                            <span class="d-md-block d-none">Add User Balance</span>
                        </button>
                        @endif
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
    <script src="{{ asset('assets/js/statusmenu.js') }}"></script>
    @include('backend.layouts.datatable-dynamic')
    <script>
        $(document).ready(function ($) {
            var menuItems=[
                {title:'<i class="fa fa-check-circle text-success status"> Approved</i>',value:'Approved'}
            ];
            $(document).on("click", "#status", function () {
                @if($approvedPermission == 'Yes')
                    $("span#status").statusMenu({items: menuItems});
                @endif
            });
        });

        $(document).on("click", "#change_status_menu", function (event) {
            if(confirm("Are you sure to approved?")) {
                event.preventDefault();
                var route = '{{ route($approvedUrl, ':id') }}';
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
                            $('.approved_date_' + obj.id).html(obj.date);
                            location.reload();
                        }
                    });
            } else {
                event.preventDefault();
            }
            return false;
        });
    </script>
@endsection
