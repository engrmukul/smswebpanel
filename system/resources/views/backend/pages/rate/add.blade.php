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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('rate.list') }}">Rate List</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('rate.add.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">Add New Rate</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="name">Rate Type</label>
                                        <div class="col-md-10 input-element {{ $errors->has('rate_type') ? 'has-error' : '' }}">
                                            <select id="rate_type" name="rate_type" class="form-control border-primary">
                                                <option value="">Select Rate Type</option>
                                                <option value="sms" {{ (old('rate_type') == 'sms')? 'selected':'' }}>SMS</option>
                                                <option value="email" {{ (old('rate_type') == 'email')? 'selected':'' }}>EMAIL</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('rate_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label label-control" for="name">Rate Name</label>
                                        <div class="col-md-10 input-element {{ $errors->has('rate_name') ? 'has-error' : '' }}">
                                            <input type="text" id="name" class="form-control border-primary" placeholder="Rate Name" name="rate_name" value="{{ old('rate_name') }}">
                                            <span class="text-danger">{{ $errors->first('rate_name') }}</span>
                                        </div>
                                    </div>

                                    <div id="sms_type" style="display: none">

                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label label-control" for="sellingrate1">Masking Rate</label>
                                            <div class="col-md-10 input-element {{ $errors->has('selling_masking_rate') ? 'has-error' : '' }}">
                                                <input type="text" id="sellingrate1" class="form-control border-primary" placeholder="0.000000" name="selling_masking_rate" value="{{ old('selling_masking_rate') }}">
                                                <span class="text-danger">{{ $errors->first('selling_masking_rate') }}</span>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label label-control" for="sellingrate2">Non Masking Rate</label>
                                            <div class="col-md-10 input-element {{ $errors->has('selling_nonmasking_rate') ? 'has-error' : '' }}">
                                                <input type="text" id="sellingrate2" class="form-control border-primary" placeholder="0.000000" name="selling_nonmasking_rate" value="{{ old('selling_nonmasking_rate') }}">
                                                <span class="text-danger">{{ $errors->first('selling_nonmasking_rate') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="email_type" style="display: none">
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label label-control" for="email_rate">Email Rate</label>
                                            <div class="col-md-10 input-element {{ $errors->has('email_rate') ? 'has-error' : '' }}">
                                                <input type="text" id="email_rate" class="form-control border-primary" placeholder="0.000000" name="email_rate" value="{{ old('email_rate') }}">
                                                <span class="text-danger">{{ $errors->first('email_rate') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Add
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('rate.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
            $('#rate_type').change(function () {
                var type = $(this).val();
                if (type == 'sms') {
                    $('#sms_type').show();
                    $('#email_type').hide();
                    $('#email_rate').val('');
                } else if (type == 'email') {
                    $('#sms_type').hide();
                    $('#email_type').show();
                    $('#sellingrate1').val('');
                    $('#sellingrate2').val('');
                } else {
                    $('#sms_type').hide();
                    $('#email_type').hide();
                    $('#email_rate').val('');
                    $('#sellingrate1').val('');
                    $('#sellingrate2').val('');
                }
            });
        });

    </script>
@endsection
