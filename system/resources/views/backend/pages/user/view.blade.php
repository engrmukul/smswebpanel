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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('user.list') }}">User List</a>
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
                                        <span class="d-md-block d-none">Edit User</span>
                                    </button>
                                </div>
                            </div>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%" class="text-right">User Name :</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                @if (empty(Auth::user()->reseller_id))
                                    <tr>
                                        <th width="20%" class="text-right">Reseller Name :</th>
                                        <td>{{ $user->reseller->reseller_name }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th width="20%" class="text-right">Username :</th>
                                    <td>{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Email :</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Address :</th>
                                    <td>{!! $user->address !!}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Mobile :</th>
                                    <td>{{ $user->mobile }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Created By :</th>
                                    <td>{{ $user->createBy->name }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">TPS :</th>
                                    <td>{{ $user->tps }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Who Can Access :</th>
                                    <td>{{ implode(', ', $assign_user_name) }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">User Status :</th>
                                    <td>{{ $user->status }}</td>
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
            var route = '{{ route('user.edit', $user->id) }}';
            window.history.pushState('', '', route);
            $.get(route).done(function(page_content){
                $('.app-content').html(page_content);
            }).fail(function (data, textStatus, xhr) {
                window.location.href = "/smspanel/dashboard";
            });
        });
    </script>
@endsection
