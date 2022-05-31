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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('menu.list') }}">Menu List</a>
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
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%" class="text-right">Menu Title :</th>
                                    <td>{{ $menu->title }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Group Permission :</th>
                                    <td>{{ implode(', ', $assign_group_name) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Route Name :</th>
                                    <td>{{ $menu->route_name }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Active Route Name :</th>
                                    <td>{{ $menu->active_route }}</td>
                                </tr>
                                <tr>
                                    <th width="20%" class="text-right">Menu Status :</th>
                                    <td>{{ $menu->status }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($menu->parent_id == 0)
        <section id="horizontal-form-layouts">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Sub Menu Of "{{ $menu->title }}"</h4>
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
                                <!-- Button trigger modal -->
                                <div class="form-group justify-content-end row">
                                    <div class="col-md-2 mb-1 pl-0">
                                        <button type="button" class="btn btn-primary block btn-lg" data-toggle="modal" data-target="#addFrom">
                                            Add Sub Menu
                                        </button>
                                    </div>
                                </div>
                                @include('backend.pages.elements.tableboard')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif

    <!-- Add sub menu modal -->
    <div class="modal fade text-left" id="addFrom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Sub Menu</label>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form form-horizontal" method="post" action="{{ route('menu.add.sub.post', $menu->id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body pt-2">
                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Menu Title</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('title') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Menu Title" name="title" value="">
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Menu Permission</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('user_group') ? 'has-error' : '' }}">
                                <div class="form-group row" style="padding-right: 15px; padding-left: 15px;">
                                    @foreach($user_groups as $user_group)
                                        <div class="col-lg-3 col-form-label custom-control custom-checkbox">
                                            <input type="checkbox" value="{{ $user_group->id }}" name="user_group[]" id="users-checkbox{{ $user_group->id }}" onclick="{{ ($user_group->id ==1)? 'return false;':'return true;' }}" {{ ($user_group->id == 1)? 'checked':'' }} class="custom-control-input">
                                            <label class="custom-control-label" for="users-checkbox{{ $user_group->id }}">{{ $user_group->title }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="text-danger">{{ $errors->first('user_group') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Parent Menu</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                                <select class="form-control custom-select block" name="parent_id" id="parent_id">
                                    @foreach($parentMenus as $pmenu)
                                        <option value="{{ $pmenu->id }}" {{ ($pmenu->id == $menu->id)? 'selected': '' }}>{{ $pmenu->title }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Route Name</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('route_name') ? 'has-error' : '' }}">
                                <select class="form-control custom-select block" name="route_name" id="route_name">
                                    <option value="">Select Route Name</option>
                                    @foreach($route_names as $route_name)
                                        <option value="{{ $route_name }}">{{ $route_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('route_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Active Route</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('active_route') ? 'has-error' : '' }}">
                                <input readonly class="form-control" placeholder="Active Route" name="active_route" id="active_route" value="{{ $menu->active_route }}">
                                <span class="text-danger">{{ $errors->first('active_route') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Order No</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('order_no') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="Order No" name="order_no" value="">
                                <span class="text-danger">{{ $errors->first('order_no') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Status</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('status') ? 'has-error' : '' }}">
                                <select class="form-control custom-select block" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 pl-3 col-form-label">Menu Icon Name</label>
                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('icon') ? 'has-error' : '' }}">
                                <input type="text" class="form-control" placeholder="feather icon-box" name="icon" value="">
                                <span class="text-danger">{{ $errors->first('icon') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-10">

                                <div class="modal-footer">
                                    <input type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal" value="close">
                                    <input type="submit" class="btn btn-outline-primary btn-lg" value="Add">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal End -->

@endsection

@section('pageJS')
    @include('backend.layouts.datatable-dynamic')
    <script src="{{ asset('assets/app-assets/js/scripts/modal/components-modal.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#parent_id').change(function (){
                var parent_id = $(this).val();
                if (parent_id !== ''){
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

            if('{{ !$errors->isEmpty() }}') {
                $('#addFrom').modal('show');
            }
        });
    </script>
@endsection
