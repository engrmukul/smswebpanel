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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('user.list') }}">Users</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('user.edit.post', $user->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Name</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Name" name="name" value="{{ $user->name }}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">User Name</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('username') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Username" name="username" value="{{ $user->username }}">
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Email</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ $user->email }}">
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Mobile</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value="{{ $user->mobile }}">
                                            <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Address</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('address') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Address" name="address" value="{{ $user->address }}">
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">User Type</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('user_type') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block" name="user_type" id="user_type">
                                                <option value="">Select User Type</option>
                                                @foreach($user_groups as $user_group)
                                                    <option value="{{ $user_group->id }}" {{ ($user->id_user_group == $user_group->id)? 'selected': '' }}>{{ $user_group->title }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('user_type') }}</span>
                                        </div>
                                    </div>

                                    @if (empty(Auth::user()->reseller_id))
                                        <div class="form-group row" id="resellerName" style="display: {{ ($errors->has('reseller_name') or $user->reseller_id) ? '' : 'none' }}">
                                            <label class="col-md-2 pl-3 col-form-label">Reseller Name</label>
                                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('reseller_name') ? 'has-error' : '' }}">
                                                <select class="form-control custom-select block" id="reseller" name="reseller_name">
                                                    <option value="">Select Reseller</option>
                                                    @foreach($resellers as $reseller)
                                                        <option value="{{ $reseller->id }}" {{ ($user->reseller_id == $reseller->id)? 'selected': '' }}>{{ $reseller->reseller_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('reseller_name') }}</span>
                                            </div>
                                        </div>
                                    @endif


                                    <div class="form-group row" id="rateName" style="display: {{ ($errors->has('rate_name') or $user->rate_id) ? '' : 'none' }}">
                                        <label class="col-md-2 pl-3 col-form-label">Sms Rate</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('rate_name') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block" id="rate" name="rate_name">
                                                <option value="">Select Sms Rate</option>
                                                @foreach($rates as $rate)
                                                    <option value="{{ $rate->id }}" {{ ($user->rate_id == $rate->id)? 'selected': '' }}>{{ $rate->rate_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('rate_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Who Can Access</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('assign_user') ? 'has-error' : '' }}">
                                            <select class="select2 form-control block" multiple="multiple" name="assign_user[]">
                                                @php($assign_id = explode(',', $user->assign_user_id))
                                                <option value="">Select User</option>
                                                @foreach($assignUsers as $assignUser)
                                                    <option value="{{ $assignUser->id }}" {{ (in_array($assignUser->id, $assign_id))? 'selected': '' }}>{{ $assignUser->name }} ({{ $assignUser->userType->title }})</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('assign_user') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Status</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('status') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block" name="status">
                                                <option value="PENDING" {{ ($user->status == 'PENDING')? 'selected': '' }}>Active</option>
                                                <option value="ACTIVE" {{ ($user->status == 'ACTIVE')? 'selected': '' }}>Active</option>
                                                <option value="INACTIVE" {{ ($user->status == 'INACTIVE')? 'selected': '' }}>Inactive</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
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
                                                <a href="{{ route('user.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
        $(document).ready(function (){
            $('#user_type').change(function (){
                var type = $(this).val();
                if (type == 1 || type == 2){
                    $('#resellerName').hide();
                    $('#reseller').prop("selectedIndex", 0)
                } else {
                    $('#resellerName').show();
                }
                if (type == 1 || type == 2){
                    $('#rateName').hide();
                    $('#rate').prop("selectedIndex", 0)
                } else {
                    $('#rateName').show();
                }
            });
            $('.select2').select2({
                width: '100%'
            });
        });

    </script>
@endsection
