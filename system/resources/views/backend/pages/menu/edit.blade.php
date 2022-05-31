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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('menu.list') }}">Menus</a>
                    </li>
                    @if($menu->parent_id != 0)
                        <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('menu.view', $menu->parent_id) }}">Parent Menu</a>
                        </li>
                    @endif
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
                                <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <form class="form form-horizontal" method="post" action="{{ route('menu.edit.post', $menu->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Menu Title</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('title') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Menu Title" name="title" value="{{ $menu->title }}">
                                            <span class="text-danger">{{ $errors->first('title') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Menu Permission</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('user_group') ? 'has-error' : '' }}">
                                            <div class="form-group row" style="padding-right: 15px; padding-left: 15px;">
                                                @foreach($user_groups as $user_group)
                                                    <div class="col-lg-3 pr-3 pl-3 col-form-label custom-control custom-checkbox">
                                                        @php($user_group_ids = explode(',',$menu->user_group_id))
                                                        <input type="checkbox" value="{{ $user_group->id }}" name="user_group[]" id="users-checkbox{{ $user_group->id }}" {{ (in_array($user_group->id, $user_group_ids) )? 'checked':'' }} class="custom-control-input">
                                                        <label class="custom-control-label" for="users-checkbox{{ $user_group->id }}">{{ $user_group->title }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('user_group') }}</span>
                                    </div>


                                    @if ($menu->parent_id != 0)
                                        <div class="form-group row">
                                            <label class="col-md-2 pl-3 col-form-label">Parent Menu</label>
                                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                                                <select class="form-control custom-select block" name="parent_id" id="parent_manu">
                                                    @foreach($parentMenus as $parentMenu)
                                                        <option value="{{ $parentMenu->id }}" {{ ($parentMenu->id == $menu->parent_id)? 'selected':'' }}>{{ $parentMenu->title }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Active Route</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('active_route') ? 'has-error' : '' }}">
                                            @if($menu->parent_id != 0)
                                                <input type="text" readonly class="form-control" id="active_route" placeholder="Active Route" name="active_route" value="{{ $menu->active_route }}">
                                            @else
                                                <select class="form-control" name="active_route" id="active_route">
                                                    <option value="">Select Active Route</option>
                                                    @foreach($activeRoutes as $activeRoute)
                                                        <option value="{{ $activeRoute }}" {{ ($menu->active_route == $activeRoute)?'selected':'' }}>{{ $activeRoute }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            <span class="text-danger">{{ $errors->first('active_route') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Route Name</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('route_name') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block" name="route_name" id="route_name">
                                                <option value="">Select Route Name</option>
                                                @foreach($route_names as $route_name)
                                                    <option value="{{ $route_name }}" {{ ($menu->route_name == $route_name)?'selected':'' }}>{{ $route_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('route_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Order No</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('order_no') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Order No" name="order_no" value="{{ $menu->order_no }}">
                                            <span class="text-danger">{{ $errors->first('order_no') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Status</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('status') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block" name="status">
                                                <option value="Active" {{ ($menu->status == 'Active')? 'selected':'' }}>Active</option>
                                                <option value="Inactive" {{ ($menu->status == 'Inactive')? 'selected':'' }}>Inactive</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Menu Icon Name</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('icon') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="feather icon-box" name="icon" value="{{ $menu->icon }}">
                                            <span class="text-danger">{{ $errors->first('icon') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10 pl-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Update
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('menu.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
        $(document).ready(function () {
            @if($menu->parent_id != 0)
            $('#parent_manu').change(function () {
                var parent_id = $(this).val();
                if (parent_id !== '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.route.name') }}",
                        data: {parent_id: parent_id, _token: '{{ csrf_token() }}'},
                        dataType: "json",
                        cache: false,
                        success: function (data) {
                            var options = '<option value="">Select Route Name</option>';
                            $.each(data.route_name, function (index, regenciesObj) {
                                options += '<option value="' + regenciesObj + '">' + regenciesObj + '</option>';
                            })
                            $('#route_name').html(options);
                            $('#active_route').val(data.active_route);
                        }
                    });
                } else {
                    $('#route_name').html('<option value="">Select Route Name</option>');
                    $('#active_route').val('');
                }
            });
            @else
            $('#active_route').change(function () {
                var active_route = $(this).val();
                if (active_route !== '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.route.name') }}",
                        data: {active_route: active_route, _token: '{{ csrf_token() }}'},
                        dataType: "json",
                        cache: false,
                        success: function (data) {
                            var options = '<option value="">Select Route Name</option>';
                            $.each(data.route_name, function (index, regenciesObj) {
                                options += '<option value="' + regenciesObj + '">' + regenciesObj + '</option>';
                            })
                            $('#route_name').html(options);
                        }
                    });
                } else {
                    $('#route_name').html('<option value="">Select Route Name</option>');
                }
            });
            @endif

        });
    </script>
@endsection

