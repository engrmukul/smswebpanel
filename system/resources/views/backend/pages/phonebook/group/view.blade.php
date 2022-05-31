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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('phonebook.group.list') }}">Group List</a>
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
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%" class="text-right">Group Name :</th>
                                    <td>{{ $group->name }}</td>
                                </tr>
                                @if(empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4)
                                    <tr>
                                        <th width="20%" class="text-right">Group Type :</th>
                                        <td>{{ $group->type }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th width="20%" class="text-right">Group Status :</th>
                                    <td>{{ $group->status }}</td>
                                </tr>

                                <tr>
                                    <th width="20%" class="text-right">Active Number :</th>
                                    <td>{{ \App\Models\Contact::where('group_id', $group->id)->where('status', 'Active')->count() }}</td>
                                </tr>

                                <tr>
                                    <th width="20%" class="text-right">Inactive Number :</th>
                                    <td>{{ \App\Models\Contact::where('group_id', $group->id)->where('status', 'Inactive')->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Contacts Of "{{ $group->name }}" Group</h4>
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
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <button link="{{route('phonebook.contact.add')}}" class="btn btn-primary btn-md submit btn-lg" data-toggle="modal" data-target="#addFrom"><i class="d-md-none d-block feather icon-plus"></i>
                                        <span class="d-md-block d-none">Add Contact</span>
                                    </button>
                                </div>
                            </div>
                            <form class="form-horizontal" id="searchForm">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-md-3 pt-1">
                                            <input type="text" class="form-control border-primary" placeholder="Search with Name, phone or email" name="common_field" value="{{ old('common_field') }}">
                                        </div>
                                        <div class="col-md-2 pt-1 pl-md-2">
                                            <select id="search_division" class="form-control custom-select block border-primary select2" name="division">
                                                <option value="">Select Division</option>
                                                @foreach($divisions as $division)
                                                    <option value="{{ $division['name'] }}" {{ (old('division') == $division['name'])? 'selected': '' }}>{{ $division['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 pt-1">
                                            <select id="search_district" class="form-control custom-select block border-primary select2" name="district">
                                                <option value="">Select District</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 pt-1">
                                            <select id="search_upazilla" class="form-control custom-select block border-primary select2" name="upazilla">
                                                <option value="">Select Upazilla</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 pt-1">
                                            <select name="gender" id="gender" class="form-control custom-select block border-primary">
                                                <option value="" {{ (old('gender') == '')? 'selected':'' }}>Gender</option>
                                                <option value="Male" {{ (old('gender') == "Male")? 'selected':'' }}>Male</option>
                                                <option value="Female" {{ (old('gender') == "Female")? 'selected':'' }}>Female</option>
                                                <option value="Other" {{ (old('gender') == "Other")? 'selected':'' }}>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 pt-1">
                                            <select id="profession" class="form-control custom-select block border-primary select2" name="profession">
                                                <option value="">Select Profession</option>
                                                @foreach($professions as $profession)
                                                    <option value="{{ $profession->profession }} {{ (old('profession') == $profession->profession)? 'selected': '' }}">{{ $profession->profession }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 pt-1">
                                            <input type="text" class="form-control border-primary" placeholder="Age or Age-Age" name="age" value="{{ old('age') }}">
                                        </div>
                                        <div class="col-md-3 pt-1">
                                            <select id="blood_group" class="form-control custom-select block border-primary select2" name="blood_group">
                                                <option value="">Select Blood Group</option>
                                                @foreach(bloodGroups() as $bGroup)
                                                    <option value="{{ $bGroup }}" {{ (old('blood_group') == $bGroup)? 'selected': '' }}>{{ $bGroup }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2 justify-content-center pt-1">
                                            <button class="btn block btn-primary" type="submit">
                                                <i class="fa fa-search-plus"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @include('backend.pages.elements.tableboard')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Contact modal -->
    <div class="modal fade text-left" id="addFrom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Contact</label>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form form-horizontal" method="post" action="{{ route('phonebook.contact.add.store', $group->id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control border-primary" placeholder="Contact Name" name="group" value="{{ $group->id }}">
                    <div class="form-body pt-2 pl-3 pr-3">
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
                                            <input type="text" id="name_bn" class="form-control border-primary" placeholder="বাংল াণা ম" name="name_bn" value="{{ old('name_bn') }}">
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
                                            <input type="text" id="gender" class="form-control border-primary" placeholder="Gender" name="gender" value="{{ old('gender') }}">
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
                                            <input type="text" id="dob" class="form-control border-primary" placeholder="DOB" name="dob" value="{{ old('dob') }}">
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
                                            <input type="text" id="blood_group" class="form-control border-primary" placeholder="Blood Group" name="blood_group" value="{{ old('blood_group') }}">
                                            <span class="text-danger">{{ $errors->first('blood_group') }}</span>
                                        </div>
                                    </div>
                                </div>
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
    <script>
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            // dom: 'Bfrtip',
            select: {
                style: 'multiple',
                selector: 'td:not(:nth-last-child(1), :nth-last-child(2))'
            },
            language: {
                processing: "<img width='75px' src='{{ asset('assets/images/loading.gif') }}'>",
            },
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, row, meta) {
                        return type != 'filter' ? meta.row + 1 : data;
                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                },
                {
                    searchable: false,
                    orderable: false,
                    className: 'd-none',
                    targets: 2
                },
            ],
            columns: [
                    @foreach($tableHeaders as $key=>$tableHeader)
                {
                    data: "{{$key}}"
                },
                @endforeach
            ],

            pageLength: 20,
            lengthMenu: [[20, 30, 50, -1], [20, 30, 50, "All"]],
            buttons: false,
            searching: false,
            ajax: "{{ $ajaxUrl }}",
            initComplete: function (settings, json) {
                var numRows = table.page.info().recordsTotal;
                $('#contact_count').html(numRows);
                $('#totalNumber').val(numRows);
            }
        });

        var frm = $('#searchForm');
        frm.submit(function (e) {
            e.preventDefault();
            var url = frm.serialize()
            table.ajax.url('{{ $ajaxUrl }}?' + url).load();
        });

        var remove_btn = '<button type="submit" id="delete" class="btn btn-small btn-danger ml-1" disabled onclick="return userDeleteFunction();"><i class="fa fa-trash"></i></button>';
        $('.dataTables_length label').after(remove_btn);
        table.on("click", "th.select-checkbox", function () {
            if ($("th.select-checkbox").hasClass("selected")) {
                table.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                table.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        }).on("select deselect", function () {
            ("Some selection or deselection going on")
            if (table.rows({
                selected: true
            }).count() !== table.rows().count()) {
                $("th.select-checkbox").removeClass("selected");
            } else {
                $("th.select-checkbox").addClass("selected");
            }
        });
        table.on('select', function (e, dt, type, indexes) {
            $('#delete').attr("disabled", false);
        }).on('deselect', function (e, dt, type, indexes) {
            // var rowData = table.rows(indexes).data().toArray();
            var rows = $('tr.selected');
            var rowData = table.rows(rows).data();
            if (rowData.length < 1) {
                $('#delete').prop("disabled", true);
            }
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel, .buttons-page-length').addClass('btn mb-2');

        function userDeleteFunction() {
            var rows = $('tr.selected');
            var rowData = table.rows(rows).data();

            if(rowData.length > 1) {
                var conName = "Selected Contacts";
            } else {
                var conName = rowData[0]['name_en'];
            }
            var selectData = [];
            $.each(rowData, function (index, regenciesObj) {
                selectData.push(
                    {id:regenciesObj.id}
                );
            });
            selectData = JSON.stringify(selectData);
            if (confirm("Are you sure to delete (" + conName + ")?")) {
                event.preventDefault();
                $('#loadingDiv').show();
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('phonebook.contact.delete') }}",
                    data: {data: selectData, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        $('#loadingDiv').hide();
                        table.ajax.url('{{ $ajaxUrl }}').load(function () {
                            var rows = table.page.info().recordsTotal;
                            $('#contact_count').html(rows);
                            $('#totalNumber').val(rows);
                            $('#campaign').val(table.ajax.json().campaign);
                            if (rows == 0) {
                                document.getElementById("createCampaign").disabled = true;
                            } else {
                                document.getElementById("createCampaign").disabled = false;
                            }
                        });
                    }
                });
            } else {
                event.preventDefault();
            }
        }
    </script>
    <script>
        $(document).ready(function ($) {
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


            // For Search
            $('#search_upazilla').select2({width: '100%'});

            $('#search_division').select2({width: '100%'}).change(function () {
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
                            $('#search_district').html(options).trigger("change.select2");
                            $('#search_upazilla').html('<option value="">Select Upazilla</option>').trigger("change.select2");
                        }
                    });
                } else {
                    $('#search_district').html('<option value="">Select District</option>').trigger("change.select2");
                    $('#search_upazilla').html('<option value="">Select Upazilla</option>').trigger("change.select2");
                }
            });

            $('#search_district').select2({width: '100%'}).change(function () {
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
                            $('#search_upazilla').html(options).trigger("change.select2");
                        }
                    });
                } else {
                    $('#search_upazilla').html('<option value="">Select Upazilla</option>').trigger("change.select2");
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/statusmenu.js') }}"></script>
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
    <script>
        $(document).ready(function ($) {
            var menuItems = [
                {title: '<i class="fa fa-check-circle text-success"> Active</i>', value: 'Active'},
                {title: '<i class="fa fa-times-circle text-danger"> Inactive</i>', value: 'Inactive'}
            ];

            $(document).on("click", "#status", function () {
                $('span#status').statusMenu({items: menuItems});
            });
        });

        $(document).on("click", "#change_status_menu", function () {
            var route = '{{ route('phonebook.contact.status', ':id') }}';
            var status_id = $(this).data('id');
            var status_value = $(this).data('value');

            console.log(status_id);
            console.log(status_value);

            route = route.replace(':id', status_id);
            $.ajax(
                {
                    type: "POST",
                    url: route,
                    data: {status: status_value, _token: '{{ csrf_token() }}'},
                    cache: false,
                    success: function (data) {
                        var obj = JSON.parse(data);
                        $('.dropdown-menu').hide();
                        $('.status_change_id_' + obj.id).html(obj.data);
                    }
                });
            return false;
        });
    </script>
    <script src="{{ asset('assets/app-assets/js/scripts/modal/components-modal.js') }}"></script>
    <script>
        $(document).ready(function () {
            if ('{{ !$errors->isEmpty() }}') {
                $('#addFrom').modal('show');
            }
        });
    </script>
@endsection
