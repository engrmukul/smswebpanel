@extends($layout)

@section('pageCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/summernote/summernote-bs4.min.css') }}">
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
                        <button link="{{ route('phonebook.contact.add') }}" class="btn btn-primary btn-md submit"><i class="d-md-none d-block feather icon-plus"></i>
                            <span class="d-md-block d-none">Add Contact</span>
                        </button>
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
                            <form class="form-horizontal" id="searchForm" enctype="multipart/form-data">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-md-3 pt-1">
                                            <input type="text" class="form-control border-primary" placeholder="Search with Name, phone or email" name="common_field" value="{{ old('common_field') }}">
                                        </div>
                                        <div class="col-md-2 pt-1 pl-md-2">
                                            <select id="division" class="form-control custom-select block border-primary select2" name="division">
                                                <option value="">Select Division</option>
                                                @foreach($divisions as $division)
                                                    <option value="{{ $division['name'] }}" {{ (old('division') == $division['name'])? 'selected': '' }}>{{ $division['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 pt-1">
                                            <select id="district" class="form-control custom-select block border-primary select2" name="district">
                                                <option value="">Select District</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 pt-1">
                                            <select id="upazilla" class="form-control custom-select block border-primary select2" name="upazilla">
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
                                        <div class="col-md-2 pt-1">
                                            <select id="group" class="form-control custom-select block border-primary select2" name="group">
                                                <option value="">Select Contact Group</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 pt-1">
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
                                        <div class="col-md-2 pt-1">
                                            <select id="blood_group" class="form-control custom-select block border-primary select2" name="blood_group">
                                                <option value="">Select Blood Group</option>
                                                @foreach(bloodGroups() as $group)
                                                    <option value="{{ $group }}" {{ (old('blood_group') == $group)? 'selected': '' }}>{{ $group }}</option>
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

                            <div class="form-actions">
                                <div class="form-group mb-0 justify-content-end row">
                                    <div class="col-md-12 pl-3">
                                        <label class="col-form-label text-right text-bold-700">Total <span id="contact_count" class="text-danger">0</span> Contact Found </label>
                                        <button id="createCampaign" class="btn btn-primary" data-toggle="modal" data-target="#addCampaign">
                                            <i class="fa fa-envelope"></i> Create Campaign
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @include('backend.pages.elements.tableboard')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Contact modal -->
    <div class="modal fade text-left" id="addCampaign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title text-text-bold-600" id="myModalLabel33">Add Campaign</label>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form form-horizontal" method="post" action="{{ route('campaign.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body pt-2 pl-3 pr-3">
                        <div class="form-group row">
                            <label class="col-12 col-form-label" for="campaign_type">Campaign Type</label>
                            <div class="col-12 input-element {{ $errors->has('campaign_type') ? 'has-error' : '' }}">
                                <select class="campaign_type form-control custom-select block border-primary" id="campaign_type" name="campaign_type">
                                    <option value="">Select Campaign Type</option>
                                    <option value="sms" {{ (old('campaign_type') == 'sms')?'selected':'' }}>SMS</option>
                                    <option value="email" {{ (old('campaign_type') == 'email')?'selected':'' }}>EMAIL</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('campaign_type') }}</span>
                            </div>
                        </div>

                        @if(empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4)
                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="user">Campaign User *</label>
                                <div class="col-12 input-element {{ $errors->has('users') ? 'has-error' : '' }}">
                                    <select class="select2 form-control block" id="user" name="user">
                                        <option value="">Select User</option>
                                    </select>
                                    <span class="text-danger">{{ $errors->first('user') }}</span>
                                </div>
                            </div>
                        @endif

                        <div id="smsShow" style="display: {{ (old('campaign_type') == 'sms')? '':'none' }}">
                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="mask">Mask Name</label>
                                <div class="col-12 input-element {{ $errors->has('mask') ? 'has-error' : '' }}">
                                    <select class="selectmask form-control custom-select block border-primary" id="mask" name="mask">
                                        <option value="">Select Mask Name</option>
                                        @foreach($senderIds as $senderId)
                                            <option value="{{ $senderId->senderID }}" {{ (old('mask') == $senderId->senderID)? 'selected': '' }}>{{ $senderId->senderID }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('mask') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="message">Message</label>
                                <div class="col-12 input-element {{ $errors->has('message') ? 'has-error' : '' }}">
                                    <textarea id="message" name="message" class="block border-primary" rows="4">{{old('message')}}</textarea>
                                    <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space, Enter or Tab to transliterate. <br>
                                    <span>Entered Char : <span name="countBox2" id="countBox2" class="text-danger text-bold-700"></span>, Number of char per SMS : <span name="actualSMSLength" id="actualSMSLength" class="text-primary text-bold-700"></span> ,Total SMS :<span name="usrSMSCnt" id="usrSMSCnt" class="text-danger text-bold-700"></span></span><br>

                                    <input type="hidden" name="page" value="" id="page"/>
                                    <input type="hidden" name="isunicode" value="" id="isunicode"/>
                                    <input type="hidden" name="smscharlength" value="" id="smscharlength"/>
                                    <input type="hidden" name="totalsmscount" value="" id="totalsmscount"/>
                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="totalNumber" value="" id="totalNumber"/>
                        <input type="hidden" id="campaign" name="search_param" value="{{ $campaign }}">

                        <div id="emailShow" style="display: {{ (old('campaign_type') == 'email')?'':'none' }}">
                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="domain">Domain</label>
                                <div class="col-12 input-element {{ $errors->has('domain') ? 'has-error' : '' }}">
                                    <select class="selectdomain form-control custom-select block border-primary" id="domain" name="domain">
                                        <option value="">Select Domain Name</option>
                                        @foreach($domains as $domain)
                                            <option value="{{ $domain->domain }}" {{ (old('domain') == $domain->domain)? 'selected': '' }}>{{ $domain->domain }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('domain') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="subject">Subject</label>
                                <div class="col-12 input-element {{ $errors->has('subject') ? 'has-error' : '' }}">
                                    <input type="text" class="selectdomain form-control block border-primary" id="subject" name="subject" value="{{ old('subject') }}">
                                    <span class="text-danger">{{ $errors->first('subject') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="from_email">From Email</label>
                                <div class="col-12 input-element {{ $errors->has('from_email') ? 'has-error' : '' }}">
                                    <input type="text" class="selectdomain form-control block border-primary" id="from_email" name="from_email" value="{{ old('from_email') }}">
                                    <span class="text-danger">{{ $errors->first('from_email') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="message">Email</label>
                                <div class="col-12 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <textarea id="email" name="email" class="block border-primary">{{old('email')}}</textarea>
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-2 col-form-label" for="schedule">Schedule : </label>
                            <div class="col-10 col-form-label custom-control custom-checkbox input-element {{ $errors->has('schedule') ? 'has-error' : '' }}">
                                <input type="checkbox" value="yes" name="schedule" id="schedule" class="form-control custom-control-input user-checkbox" onclick="scheduleFunction();">
                                <label class="custom-control-label" for="schedule">YES</label>
                                <span class="text-danger">{{ $errors->first('schedule') }}</span>
                            </div>
                        </div>

                        <div class="form-group row" id="scheduleTime" style="display: {{ ($errors->has('schedule_time') or old('schedule_time') != '')  ? '' : 'none' }}">
                            <label class="col-12 col-form-label" for="schedule_time">Schedule Time</label>
                            <div class="col-12 input-element {{ $errors->has('schedule_time') ? 'has-error' : '' }}">
                                <div class="input-group">
                                    <input type="text" autocomplete="off" id="schedule_time" class="form-control border-primary" value="{{old('schedule_time')}}" name="schedule_time" placeholder="yyyy-mm-dd">
                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('schedule_time') }}</span>
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
    <script src="{{ asset('assets/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('assets/js/banglaType.js') }}"></script>
    <script src="{{ asset('assets/app-assets/js/scripts/modal/components-modal.js') }}"></script>
    <script>

        function scheduleFunction() {
            if ($('#schedule').is(':checked')) {
                $('#scheduleTime').show();
            } else {
                $('#scheduleTime').hide();
                $('#schedule_time').val('');
            }
        }

        $(document).ready(function () {
            // Select2
            $("#user").select2({
                width: '100%',
                ajax: {
                    url: '{{ route('select.user') }}',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            _token: '{{ csrf_token() }}'
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            $('#users').change(function () {
                var user_id = $(this).val();
                if (user_id != '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.user.mask.domain') }}",
                        data: {user_id: user_id, _token: '{{ csrf_token() }}'},
                        dataType: "json",
                        cache: false,
                        success: function (data) {
                            var maskOptions = '<option value="">Select Mask Name</option>';
                            $.each(data.masks, function (index, regenciesObj) {
                                maskOptions += '<option value="' + regenciesObj.senderID + '">' + regenciesObj.senderID + '</option>';
                            })
                            $('#mask').html(maskOptions);

                            var domainOptions = '<option value="">Select Domain Name</option>';
                            $.each(data.domains, function (index, regenciesObj) {
                                domainOptions += '<option value="' + regenciesObj.domain + '">' + regenciesObj.domain + '</option>';
                            })
                            $('#domain').html(domainOptions);
                        }
                    });
                } else {
                    $('#mask').html('<option value="">Select Mask Name</option>');
                    $('#domain').html('<option value="">Select Domain Name</option>');
                }
            });

            $('#domain').change(function () {
                var domain = $(this).val();
                if (domain != '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get.from.email') }}",
                        data: {domain: domain, _token: '{{ csrf_token() }}'},
                        dataType: "json",
                        cache: false,
                        success: function (data) {
                            console.log(data)
                            $('#from_email').val(data.from_email);
                        }
                    });
                } else {
                    $('#from_email').val('');
                }
            });

            $('#email').summernote({
                height: "200px",
                callbacks: {
                    onImageUpload: function (files) {
                        url = '{{ route('upload.image').'?_token='.csrf_token() }}'; //path is defined as data attribute for  textarea
                        sendFile(files[0], url, $(this));
                    }
                }
            });

            function sendFile(file, url, editor) {
                var data = new FormData();
                data.append("file", file);
                var request = new XMLHttpRequest();
                request.open('POST', url, true);
                request.onload = function () {
                    if (request.status >= 200 && request.status < 400) {
                        // Success!
                        var resp = $.parseJSON(request.responseText);
                        editor.summernote('insertImage', resp.url);
                    } else {
                        // We reached our target server, but it returned an error
                        var resp = request.responseText;
                    }
                };
                request.onerror = function (jqXHR, textStatus, errorThrown) {
                    // There was a connection error of some sort
                    console.log(jqXHR);
                };
                request.send(data);
            }

            $('#campaign_type').change(function () {
                var type = $(this).val();
                if (type == 'sms') {
                    $('#smsShow').show();
                    $('#emailShow').hide();
                    Emaileditor.setData('');
                } else if (type == 'email') {
                    $('#smsShow').hide();
                    $('#emailShow').show();
                    $('#message').val('');
                    $('#mask').prop("selectedIndex", 0);
                } else {
                    $('#smsShow').hide();
                    $('#emailShow').hide();
                    $('#message').val('');
                    Emaileditor.setData('');
                    $('#domain').prop("selectedIndex", 0);
                }
            });


            $("#schedule_time").datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                // sideBySide : true,
                keepOpen: false,
                showClose: true,
                tooltips: {close: 'Close Picker'},
            });

            $(function () {
                $('#message').avro({'bangla': false});
            });


            // Sms Count Start
            $("#countBox2").html(0);
            $("#actualSMSLength").html(0);
            $("#usrSMSCnt").html(0);

            function getSMSType(usrSms) {
                var smsType;
                if (jQuery.trim(usrSms).match(/[^\x00-\x7F]+/) !== null) {
                    smsType = "unicode";
                } else {
                    var newSMS = usrSms.match(/(\u000C|\u005e|\u007B|\u007D|\u005c|\u005c|\u005B|\u007E|\u005D|\u007C|\u20ac)/g);
                    if (newSMS !== null) {
                        smsType = "gsmextended";
                    } else {
                        smsType = "plaintext";
                    }
                }
                return smsType;
            }

            function calculateSMSs() {
                var content = $('#message').val();
                var newLines = content.match(/(\r\n|\n|\r)/g);
                var addition = 0;
                if (newLines != null) {
                    addition = newLines.length;
                }

                var smsType = getSMSType(content);


                usrSMSCharLength = content.length + addition;
                // $("#countBox2").html(usrSMSCharLength);
                //alert(getSMSType(content));
                if (getSMSType(content) === 'plaintext') {
                    if (usrSMSCharLength <= 160) {
                        actualSMSLength = 160;
                        usrSMSCnt = 1;
                    } else {
                        actualSMSLength = 160 - 7;
                        usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
                    }
                } else if (getSMSType(content) === 'gsmextended') {
                    if (usrSMSCharLength <= 140) {
                        actualSMSLength = 140;
                        usrSMSCnt = 1;
                    } else {
                        actualSMSLength = 140 - 6;
                        usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
                    }
                } else if (getSMSType(content) === 'unicode') {
                    if (usrSMSCharLength <= 70) {
                        actualSMSLength = 70;
                        usrSMSCnt = 1;
                    } else {
                        actualSMSLength = 70 - 3;
                        usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
                    }
                }
                $("#countBox2").html(usrSMSCharLength);
                $("#actualSMSLength").html(actualSMSLength);
                $("#usrSMSCnt").html(usrSMSCnt);
                $("#totalsmscount").val(usrSMSCnt);
                $("#isunicode").val(smsType);

            }

            //Sms Count End

            $("#message").on('keyup', function () {
                calculateSMSs();
            });
        });
    </script>
    <script>

        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            // dom: 'Bfrtip',
            select: {
                style: 'multiple',
                // selector: 'td:not(:nth-last-child(1), :nth-last-child(2))'
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
            table.ajax.url('{{ $ajaxUrl }}?' + url).load(function () {
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
    <script src="{{ asset('assets/js/statusmenu.js') }}"></script>
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

        $(document).ready(function () {
            $('#upazilla').select2({width: '100%'});

            $('#division').select2({width: '100%'}).change(function () {
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

            $('#district').select2({width: '100%'}).change(function () {
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

            $("#group").select2({
                ajax: {
                    url: '{{ route('get.contact.group') }}',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            _token: '{{ csrf_token() }}'
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

        });

        $(document).ready(function () {
            if ('{{ !$errors->isEmpty() }}') {
                $('#addCampaign').modal('show');
            }
        });
    </script>
@endsection
