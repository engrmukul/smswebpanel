@extends($layout)
@section('pageVendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">
@endsection

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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('phonebook.contact.list') }}">Contact List</a>
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
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">

                            <form class="form form-horizontal" method="post" action="{{ route('phonebook.contact.add.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body pt-2">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label" for="group">Group Name</label>
                                        <div class="col-md-10 input-element {{ $errors->has('group') ? 'has-error' : '' }}">
                                            <select id="group" class="form-control custom-select block border-primary" name="group">
                                                <option value="">Select Group</option>
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('group') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label" for="file">File Upload</label>
                                        <div class="col-md-10 input-element {{ $errors->has('file') ? 'has-error' : '' }}">
                                            <div class="form-group input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control border-primary custom-file-input" name="file" id="inputGroupFile01" accept=".csv, .xls, .xlsx">
                                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                </div>
                                                <button type="button" class="btn btn-danger" id="fileClear"> Remove</button>
                                            </div>
                                            <span class="text-danger">File Type xlsx, xls, or CSV File. Example File:
                                                <span class="text-primary">
                                                    <a href="{{ asset('assets/example/contact.csv') }}">CSV </a> |
                                                    <a href="{{ asset('assets/example/contact.xls') }}"> XLS/XLSX</a>
                                                </span>
                                            </span>
                                            @if (isset($errors) && $errors->any())
                                                @for ($i = 0; $i < 2; $i++)
                                                    @if (array_key_exists($i, $errors->all()))
                                                        <span class="text-danger">{{ $errors->first($i) }}</span><br>
                                                    @endif
                                                @endfor
                                            @endif
                                            <span class="text-danger">{{ $errors->first('file') }}</span>
                                        </div>
                                    </div>


                                    <div id="other_info">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="name_en">Name English <i class="fa fa-star danger"></i></label>
                                                    <div class="col-md-8 input-element {{ $errors->has('name_en') ? 'has-error' : '' }}">
                                                        <input type="text" id="name_en" class="form-control border-primary" placeholder="Name English" name="name_en" value="{{ old('name_en') }}">
                                                        <span class="text-danger">{{ $errors->first('name_en') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label text-md-right" for="name_bn">Name Bangla</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('name_bn') ? 'has-error' : '' }}">
                                                        <input type="text" id="name_bn" class="form-control border-primary" placeholder="বাংলা নাম" name="name_bn" value="{{ old('name_bn') }}">
                                                        <span class="text-danger">{{ $errors->first('name_bn') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="phone">Phone No</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('phone') ? 'has-error' : '' }}">
                                                        <input type="text" id="phone" class="form-control border-primary" placeholder="Phone No" name="phone" value="{{ old('phone') }}">
                                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label text-md-right" for="email">Email</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                                        <input type="text" id="email" class="form-control border-primary" placeholder="Email" name="email" value="{{ old('email') }}">
                                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="profession">Profession</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('profession') ? 'has-error' : '' }}">
                                                        <input type="text" id="profession" class="form-control border-primary" placeholder="Profession" name="profession" value="{{ old('profession') }}">
                                                        <span class="text-danger">{{ $errors->first('profession') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label text-md-right" for="gender">Gender</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('gender') ? 'has-error' : '' }}">
                                                        <select name="gender" id="gender" class="form-control custom-select block border-primary">
                                                            <option value="" {{ (old('gender') == '')? 'selected':'' }}>Select Gender</option>
                                                            <option value="Male" {{ (old('gender') == "Male")? 'selected':'' }}>Male</option>
                                                            <option value="Female" {{ (old('gender') == "Female")? 'selected':'' }}>Female</option>
                                                            <option value="Other" {{ (old('gender') == "Other")? 'selected':'' }}>Other</option>
                                                        </select>
                                                        <span class="text-danger">{{ $errors->first('gender') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="dob">Date Of Birth</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('dob') ? 'has-error' : '' }}">
                                                        <div class="input-group">
                                                            <input type="text" autocomplete="off" id="dob" class="form-control border-primary" value="{{ old('dob') }}" name="dob" placeholder="yyyy-mm-dd">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="text-danger">{{ $errors->first('dob') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label text-md-right" for="division">Division</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('division') ? 'has-error' : '' }}">
                                                        <select id="division" class="form-control custom-select block border-primary select2" name="division">
                                                            <option value="">Select Division</option>
                                                            @foreach($divisions as $division)
                                                                <option value="{{ $division['name'] }}">{{ $division['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger">{{ $errors->first('division') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="district">District</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('district') ? 'has-error' : '' }}">
                                                        <select id="district" class="form-control custom-select block border-primary select2" name="district">
                                                            <option value="">Select District</option>
                                                        </select>
                                                        <span class="text-danger">{{ $errors->first('district') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label text-md-right" for="upazilla">Upazilla/Thana</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('upazilla') ? 'has-error' : '' }}">
                                                        <select id="upazilla" class="form-control custom-select block border-primary select2" name="upazilla">
                                                            <option value="">Select Upazilla</option>
                                                        </select>
                                                        <span class="text-danger">{{ $errors->first('upazilla') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label" for="blood_group">Blood Group</label>
                                                    <div class="col-md-8 input-element {{ $errors->has('blood_group') ? 'has-error' : '' }}">
                                                        <select id="blood_group" class="form-control custom-select block border-primary select2" name="blood_group">
                                                            <option value="">Select Blood Group</option>
                                                            @foreach(bloodGroups() as $group)
                                                            <option value="{{ $group }}">{{ $group }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="text-danger">{{ $errors->first('blood_group') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10">

                                            <button type="submit" class="btn btn-primary generalFormSubmit">
                                                <code class="btn btn-sm d-none generalFormSubmitSpinner"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</code>
                                                <i class="fa fa-check-square-o generalFormSubmitButtonText"></i> Add
                                            </button>


                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('phonebook.group.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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

@section('pageVendorJS')
    <script src="{{ asset('assets/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('pageJS')
    <script>
        $(document).ready(function ($) {
            $('#group').select2({ width: '100%' });
            $('#upazilla').select2({ width: '100%' });

            $('#division').select2({ width: '100%' }).change(function () {
                var division = $(this).val();
                if (division != '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.district') }}",
                        data: {div_id: division, _token: '{{ csrf_token() }}'},
                        dataType: "json",
                        cache: false,
                        success: function (data) {
                            var options = '';
                            $.each(data, function (index, regenciesObj) {
                                options += '<option value="' + regenciesObj.id + '">' + regenciesObj.text + '</option>';
                            })
                            $('#district').html(options).trigger("change.select2");
                            $('#upazilla').html('<option value="">Select Upazilla</option>').trigger("change.select2");
                        }
                    });
                } else {
                    $('#district').html('<option value="">Select District</option>').trigger("change.select2");
                    $('#upazilla').html('<option value="">Select Upazilla</option>').trigger("change.select2");
                }
            });

            $('#district').select2({ width: '100%' }).change(function () {
                var district = $(this).val();
                if (district != '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.upazilla') }}",
                        data: {dis_id: district, _token: '{{ csrf_token() }}'},
                        dataType: "json",
                        cache: false,
                        success: function (data) {
                            var options = '';
                            $.each(data, function (index, regenciesObj) {
                                options += '<option value="' + regenciesObj.id + '">' + regenciesObj.text + '</option>';
                            })
                            $('#upazilla').html(options).trigger("change.select2");
                        }
                    });
                } else {
                    $('#upazilla').html('<option value="">Select Upazilla</option>').trigger("change.select2");
                }
            });

            $("#dob").datetimepicker({
                format: 'YYYY-MM-DD',
                // sideBySide : true,
                keepOpen: false,
                showClose: true,
                tooltips: {close: 'Close Picker'},
            });
        });
    </script>
    <script>
        $('#fileClear').on('click', function () {
            $('.custom-file-label').html('Choose file');
            $('#inputGroupFile01').val(null);
            $("#other_info").show();
        });

        $('.custom-file input').change(function (e) {
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        });

        $(".custom-file-input").change((function (e) {
            var hasClass = $("#inputGroupFile01").hasClass("custom-file-input");
            e.preventDefault();

            if (hasClass === true) {
                $("#other_info").hide();
            }

        }));
    </script>
@endsection
