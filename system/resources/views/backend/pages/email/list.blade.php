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
    <script>
        {{--$(document).on("click", ".submit", function () {--}}
        {{--    var url = '{{ route('email.add') }}';--}}
        {{--    window.history.pushState('', '', url);--}}
        {{--    $.get(url).done(function(page_content){--}}
        {{--        $('.app-content').html(page_content);--}}
        {{--    }).fail(function (data, textStatus, xhr) {--}}
        {{--        window.location.href = "/smspanel/dashboard";--}}
        {{--    });--}}
        {{--});--}}
        function deleteEmail(url, id) {
            if(confirm("Are you sure to delete thid Email?")){
                event.preventDefault();
                document.getElementById('delete'+id).action = url;
                document.getElementById('delete'+id).submit();
            } else {
                event.preventDefault();
            }
        }
    </script>
@endsection