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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('reseller.list') }}">Resellers</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('reseller.edit.post', $edit->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Reseller Name</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('reseller_name') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Reseller Name" name="reseller_name" value="{{ $edit->reseller_name }}">
                                            <span class="text-danger">{{ $errors->first('reseller_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Phone</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('phone') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Phone Number" name="phone" value="{{ $edit->phone }}">
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Email</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ $edit->email }}">
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Address</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('address') ? 'has-error' : '' }}">
                                            <textarea class="form-control" placeholder="Address" name="address">{{ $edit->address }}</textarea>
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Thana/Upzilla</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('thana') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Thana/Upzilla" name="thana" value="{{ $edit->thana }}">
                                            <span class="text-danger">{{ $errors->first('thana') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">District</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('district') ? 'has-error' : '' }}">
                                            <input type="text"  class="form-control" placeholder="District" name="district" value="{{ $edit->district }}">
                                            <span class="text-danger">{{ $errors->first('district') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">SMS Rate</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('sms_rate') ? 'has-error' : '' }}">
                                            <select class="form-control block" name="sms_rate" required>
                                                <option value="">Select SMS Rate</option>
                                                @foreach($smsRates as $smsRate)
                                                    <option value="{{ $smsRate->id }}" {{ ($edit->sms_rate_id == $smsRate->id)? 'selected': '' }}>{{ $smsRate->rate_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('sms_rate') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Email Rate</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('email_rate') ? 'has-error' : '' }}">
                                            <select class="form-control block" name="email_rate" required>
                                                <option value="">Select Rate</option>
                                                @foreach($emailRates as $emailRate)
                                                    <option value="{{ $emailRate->id }}" {{ ($edit->email_rate_id == $emailRate->id)? 'selected': '' }}>{{ $emailRate->rate_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('email_rate') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">TPS</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('tps') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" name="tps" value="{{ $edit->tps }}">
                                            <span class="text-danger">{{ $errors->first('tps') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label">Reseller Url</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('site_url') ? 'has-error' : '' }}">
                                            <input type="text" class="form-control" placeholder="Site Url" name="site_url" value="{{ $configdata->site_url }}">
                                            <span class="text-danger">{{ $errors->first('site_url') }}</span>
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
                                                <a href="{{ route('reseller.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
