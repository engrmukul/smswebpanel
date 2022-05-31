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
                            <form class="form form-horizontal" method="post" action="{{ route('user.add.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>
                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Name</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name') }}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">User Name</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('username') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Username" name="username" value="{{ old('username') }}">
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Email</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Password</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('password') ? 'has-error' : '' }}">
                                            <input type="password" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}">
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Confirm Password</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                            <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Mobile</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value="{{ old('mobile') }}">
                                            <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Address</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('address') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Address" name="address" value="{{ old('address') }}">
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">User Type</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('user_type') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block" name="user_type" id="user_type">
                                                <option value="">Select User Type</option>
                                                @foreach($user_groups as $user_group)
                                                    <option value="{{ $user_group->id }}" {{ (old('user_type') == $user_group->id)? 'selected': '' }}>{{ $user_group->title }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('user_type') }}</span>
                                        </div>
                                    </div>
                                    @if (empty(Auth::user()->reseller_id))
                                        <div class="form-group row" id="resellerName" style="display: {{ ($errors->has('reseller_name') or old('reseller_name') != '') ? '' : 'none' }}">
                                            <label class="col-md-2 pl-3 col-form-label">Reseller Name</label>
                                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('reseller_name') ? 'has-error' : '' }}">
                                                <select class="form-control custom-select block" id="reseller_name" name="reseller_name">
                                                    <option value="">Select User Type</option>
                                                    @foreach($resellers as $reseller)
                                                        <option value="{{ $reseller->id }}" {{ (old('reseller_name') == $reseller->id)? 'selected': '' }}>{{ $reseller->reseller_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('reseller_name') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group row" id="tpsShow" style="display: input-element {{ $errors->has('tps') ? '' : 'none' }}">
                                        <label class="col-md-2 pl-3 col-form-label">TPS</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('tps') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" name="tps" value="{{ old('tps') }}">
                                            <span class="text-danger">{{ $errors->first('tps') }}</span>
                                        </div>
                                    </div>
                                    <div id="rate" style="display: input-element {{ $errors->has('sms_rate')? '':'none' }}">
                                        <div class="form-group row">
                                            <label class="col-md-2 pl-3 col-form-label">SMS Rate</label>
                                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('sms_rate') ? 'has-error' : '' }}">
                                                <select class="form-control block" id="sms_rate" name="sms_rate">
                                                    <option value="">Select SMS Rate</option>
                                                    @foreach($smsRates as $smsRate)
                                                        <option value="{{ $smsRate->id }}" {{ (old('sms_rate') == $smsRate->id)? 'selected': '' }}>{{ $smsRate->rate_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('sms_rate') }}</span>
                                            </div>
                                        </div>
                                        @if(config('constants.EMAIL_SERVICE')==true)
                                        <div class="form-group row">
                                            <label class="col-md-2 pl-3 col-form-label">Email Rate</label>
                                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('email_rate') ? 'has-error' : '' }}">
                                                <select class="form-control block" id="email_rate" name="email_rate">
                                                    <option value="">Select Email Rate</option>
                                                    @foreach($emailRates as $emailRate)
                                                        <option value="{{ $emailRate->id }}" {{ (old('email_rate') == $emailRate->id)? 'selected': '' }}>{{ $emailRate->rate_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('email_rate') }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group row" id="user_billing_type" style="display: none">
                                            <label class="col-md-2 pl-3 col-form-label">Billing Type</label>
                                            <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('billing_type') ? 'has-error' : '' }}">
                                                <select class="form-control block" id="billing_type" name="billing_type">
                                                    <option value="">Select Billing Type</option>
                                                    <option value="prepaid" {{ (old('billing_type') == 'prepaid')? 'selected': '' }}>Prepaid</option>
                                                    <option value="postpaid" {{ (old('billing_type') == 'postpaid')? 'selected': '' }}>Postpaid</option>
                                                </select>
                                                <span class="text-danger">{{ $errors->first('billing_type') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label" for="dipping">Dipping</label>
                                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('dipping') ? 'has-error' : '' }}">
                                            <select id="dipping" class="form-control custom-select block" name="dipping">
                                                <option value="Inactive" {{ (old('dipping') == 'Inactive')? 'selected': '' }}>Inactive</option>
                                                <option value="Active" {{ (old('dipping') == 'Active')? 'selected': '' }}>Active</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('dipping') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Who Can Access</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('assign_user') ? 'has-error' : '' }}">
                                            <select class="select2 form-control block" multiple="multiple" name="assign_user[]">
                                                @foreach($users as $user)
                                                    @if($user->userType)
                                                    <option value="{{ $user->id }}" {{ (old('assign_user') == $user->id)? 'selected': '' }}>{{ $user->name }} ({{ $user->userType->title }})</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('assign_user') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10 pl-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Add
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
        $(document).ready(function () {
            $('#user_type').change(function () {
                var type = $(this).val();
                if (type == 3) {
                    $('#resellerName').show();
                } else {
                    $('#resellerName').hide();
                    $('#reseller_name').prop("selectedIndex", 0);
                }

                if (type == 4) {
                    $('#tpsShow').show();
                    $('#rate').show();
                } else {
                    $('#tpsShow').hide();
                    $('#rate').hide();
                    $('#sms_rate').prop("selectedIndex", 0);
                    $('#email_rate').prop("selectedIndex", 0);
                    $('#billing_type').prop("selectedIndex", 0);
                    $('#tps').val('');
                }

                if('{{ empty(Auth::user()->reseller_id) }}' && type==4){
                    $('#user_billing_type').show();
                } else {
                    $('#user_billing_type').hide();
                    $('#billing_type').prop("selectedIndex", 0);
                }
            });
            $('.select2').select2({
                placeholder: "Select Assign User"
            });
        });

    </script>
@endsection
