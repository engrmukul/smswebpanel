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
                            <form class="form form-horizontal" method="post" action="{{ route('phonebook.contact.edit.post', $contact->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label" for="group">Group</label>
                                        <div class="col-md-10 input-element {{ $errors->has('group') ? 'has-error' : '' }}">
                                            <select id="group" class="form-control custom-select block border-primary" name="group">
                                                <option value="">Select Group</option>
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}" {{ ($contact->group_id == $group->id)? 'selected': '' }}>{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('group') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label" for="name_en">Name English <i class="fa fa-star danger"></i></label>
                                                <div class="col-md-8 input-element {{ $errors->has('name_en') ? 'has-error' : '' }}">
                                                    <input type="text" id="name_en" class="form-control border-primary" placeholder="Name English" name="name_en" value="{{ $contact->name_en }}">
                                                    <span class="text-danger">{{ $errors->first('name_en') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label text-md-right" for="name_bn">Name Bangla</label>
                                                <div class="col-md-8 input-element {{ $errors->has('name_bn') ? 'has-error' : '' }}">
                                                    <input type="text" id="name_bn" class="form-control border-primary" placeholder="বাংলা নাম" name="name_bn" value="{{ $contact->name_bn }}">
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
                                                    <input type="text" id="phone" class="form-control border-primary" placeholder="Phone No" name="phone" value="{{ $contact->phone }}">
                                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label text-md-right" for="email">Email</label>
                                                <div class="col-md-8 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                                    <input type="text" id="email" class="form-control border-primary" placeholder="Email" name="email" value="{{ $contact->email }}">
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
                                                    <input type="text" id="profession" class="form-control border-primary" placeholder="Profession" name="profession" value="{{ $contact->profession }}">
                                                    <span class="text-danger">{{ $errors->first('profession') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label text-md-right" for="gender">Gender</label>
                                                <div class="col-md-8 input-element {{ $errors->has('gender') ? 'has-error' : '' }}">
                                                    <select name="gender" id="gender" class="form-control custom-select block border-primary">
                                                        <option value="" {{ ($contact->gender == null)? 'selected':'' }}>Select Gender</option>
                                                        <option value="Male" {{ ($contact->gender == "Male")? 'selected':'' }}>Male</option>
                                                        <option value="Female" {{ ($contact->gender == "Female")? 'selected':'' }}>Female</option>
                                                        <option value="Other" {{ ($contact->gender == "Other")? 'selected':'' }}>Other</option>
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
                                                        <input type="text" autocomplete="off" id="dob" class="form-control border-primary" value="{{ $contact->dob }}" name="dob" placeholder="yyyy-mm-dd">
                                                        <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="fa fa-calendar"></i></span>
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
                                                        @foreach($divisions as $division)
                                                            <option value="{{ $division['name'] }}" {{ ($contact->division == $division['name'])? 'selected': '' }}>{{ $division['name'] }}</option>
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
                                                        @foreach($districts as $district)
                                                            <option value="{{ $district['name'] }}" {{ ($contact->district == $district['name'])? 'selected': '' }}>{{ $district['name'] }}</option>
                                                        @endforeach
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
                                                        @foreach($upazillas as $upazzilla)
                                                            <option value="{{ $upazzilla['name'] }}" {{ ($contact->upazilla == $upazzilla['name'])? 'selected': '' }}>{{ $upazzilla['name'] }}</option>
                                                        @endforeach
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
                                                            <option value="{{ $group }}" {{ ($contact->blood_group == $group)? 'selected': '' }}>{{ $group }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('blood_group') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-contact mb-0 justify-content-end row">
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Update
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('phonebook.contact.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
@endsection
