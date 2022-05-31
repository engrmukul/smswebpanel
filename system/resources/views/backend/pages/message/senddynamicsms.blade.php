@extends($layout)

@section('pageVendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">
@endsection

@section('pageCSS')
    <style>
        .table.table-sm th, .table.table-sm td {
            padding: 0.3rem;
        }
    </style>
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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('message.list') }}">Mesagges</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-xl-6 col-lg-12">
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
                    <div class="card-content">
                        <div class="card-body" id="upload">

                            <form class="form form-horizontal" method="post" action="{{ route('message.add.dynamic.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" id="status" name="status" value="Queue" />
                                <div class="form-body px-1 pt-1">

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="mask">SenderID</label>
                                        <div class="col-12 input-element {{ $errors->has('mask') ? 'has-error' : '' }}">
                                            <select class="selectmask form-control custom-select block border-primary" id="mask" name="mask">
                                                <option value="">Select SenderID</option>
                                                @foreach($senderIds as $senderId)
                                                    <option value="{{ $senderId->senderID }}" {{ (old('mask') == $senderId->senderID)? 'selected': '' }}>{{ $senderId->senderID }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('mask') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label">Message File</label>
                                        <div class="col-12 input-element {{ $errors->has('file') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control border-primary custom-file-input" name="file" id="contactsFile" accept=".csv, .xls, .xlsx">
                                                    <label class="custom-file-label" for="contactsFile">Choose file</label>
                                                </div>
                                                <button type="button" class="btn btn-danger" id="fileClear"> Remove</button>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('file') }}</span>
                                            <span class="text-danger">File Type xlsx, xls, or CSV File. Example File:
                                                    <span class="text-primary">
                                                        <a href="{{ asset('assets/example/dynamic.csv') }}">CSV </a> |
                                                        <a href="{{ asset('assets/example/dynamic.xls') }}"> XLS/XLSX</a>
                                                    </span>
                                                </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="mobile_no_column">Number Column</label>
                                        <div class="col-12 input-element {{ $errors->has('mobile_no_column') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block border-primary" name="mobile_no_column" id="mobile_no_column">
                                                <option value="">Select Number Column</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('mobile_no_column') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="template">Template</label>
                                        <div class="col-12 input-element {{ $errors->has('template') ? 'has-error' : '' }}">
                                            <select class="form-control custom-select block border-primary" name="template" id="template">
                                                <option value="">Select Template</option>
                                                @foreach($templates as $template)
                                                    <option value="{{ $template->description }}">{{ $template->title }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('template') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="message">Message</label>
                                        <div class="col-12 input-element {{ $errors->has('message') ? 'has-error' : '' }}">
                                            <div class="text-right pb-1"><span id="columns"></span></div>
                                            <textarea id="message" name="message" class="block border-primary" rows="4"></textarea>
                                            <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space, Enter or Tab to transliterate. <br>
                                            <span>Total SMS :<span name="usrSMSCnt" id="usrSMSCnt" class="text-danger text-bold-700">SMS Count Depend On File</span></span><br>
                                            <span class="text-danger">{{ $errors->first('message') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label" for="schedule">Schedule Message : </label>
                                        <div class="col-9 col-form-label custom-control custom-checkbox input-element {{ $errors->has('schedule') ? 'has-error' : '' }}">
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
                                        <div class="col-md-12 pl-2">
                                            <button type="submit" id="saveAsDraft" value="Draft" name="submit" class="btn btn-primary">
                                                <i class="fa fa-first-order"></i> Save As Draft
                                            </button>
                                            <button type="submit" id="SendSMSNow" value="Send" name="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Send
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('message.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-12">
                @if(!empty(Auth::user()->reseller_id) || Auth::user()->id_user_group == config('constants.USER_GROUP'))
                    <div class="row">
                        @if(!empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != config('constants.USER_GROUP'))
                            <div class="col-xl-4 col-lg-6 col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="media align-items-stretch">
                                            <div class="p-2 bg-danger white media-body">
                                                <h3 class="text-bold-700">{{ $resellerBalance->available_balance }} BDT</h3>
                                                <h5>Balance</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-xl-4 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 bg-primary white media-body">
                                            <h3 class="text-bold-700">{{ $userBalance->masking_balance }}</h3>
                                            <h5>Masking Balance</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 bg-blue white media-body">
                                            <h3 class="text-bold-700">{{ $userBalance->non_masking_balance }}</h3>
                                            <h5>Non Mask Balance</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-content collpase show">
                        <div class="card-header mb-1">
                            <h4 class="card-title">Terms of SMS Content</h4>
                        </div>
                        <hr>
                        <ul>
                            <li>160 Characters are counted as 1 SMS in case of English language & 70 in other language.</li>
                            <li>One simple text message containing extended GSM character set (~^{}[]\|€) is of 140 characters long. Check your SMS count before pushing SMS.</li>
                            <li>Check your balance before send SMS</li>
                            <li>Number format must be start with 88, for example 8801727000000</li>
                            <li>You may send up to 3 sms size in a single try.</li>
                            <li>Seprate Numbers by Comma. For example: 8801727000000,8801727000001</li>
                        </ul>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
    <script src="{{ asset('assets/js/banglaType.js') }}"></script>
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
            $("#schedule_time").datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                // sideBySide : true,
                keepOpen: false,
                showClose: true,
                tooltips: {close: 'Close Picker'},
            });

            $(function () {
                $('#message').avro({'bangla':false});
            });

            $('#fileClear').on('click', function () {
                $('.custom-file-label').html('Choose file');
                $('#contactsFile').val(null);
            });

            $("#template").on("change", function () {
                var template_msg = $(this).val();
                $("#message").val(template_msg);
                calculateSMSs();
            });


            // parsing file data
            var parseResult = [];
            var columns = [];

            $('#contactsFile').change(function (event) {
                /*Checks whether the browser supports HTML5*/
                if (typeof (FileReader) != "undefined") {


                    parseResult = [];
                    columns = [];

                    $('#columns, #mobile_no_column').empty();

                    if (event.target.files.length > 0) {
                        var extension = event.target.files[0].name.split('.').pop().toLowerCase();
                        var fileName = event.target.files[0].name;
                        $(this).next('.custom-file-label').html(fileName);
                        var reader = new FileReader();

                        reader.readAsBinaryString(event.target.files[0]);
                        reader.onload = function (e) {
                            var data = e.target.result;

                            if (extension == 'csv') {
                                var allRows = data.split(/\r?\n|\r/);
                                var headers = [];
                                if (allRows.length > 0) {
                                    headers = allRows.shift().split(',');
                                    for (var index in allRows) {
                                        var data = allRows[index].split(',');
                                        var obj = {};
                                        var i = 0;
                                        for (var key of headers) {
                                            obj[key] = data[i];
                                            i++;
                                        }
                                        parseResult.push(obj);
                                    }
                                }

                            } else if (extension == 'xlsx' || extension == 'xls') {
                                var wb = XLSX.read(data, {type: 'binary'});
                                parseResult = XLSX.utils.sheet_to_row_object_array(wb.Sheets[wb.SheetNames[0]]);
                            } else {
                                alert('unsupported file type!');
                            }

                            if (parseResult.length > 0) {
                                $('#mobile_no_column').append('<option disabled selected>Select Number Column</option>');
                                for (var arr of parseResult) {
                                    if (Object.keys(arr).length > columns.length) columns = Object.keys(arr);
                                }
                                for (var column of columns) {
                                    $('#columns').append('<span class="badge badge-primary" style="cursor:pointer; border-radius:10px !important">{' + column + '}</span> ');
                                    $('#mobile_no_column').append('<option value="' + column + '">' + column + '</option>');
                                }
                            }
                        };
                    }
                } else {
                    alert("Sorry! Your browser does not support HTML5!");
                }
            });

            $('body').on('click', '#columns span', function () {
                var myField = $('#message').get(0);
                var myValue = $(this).text();
                if (document.selection) { //IE support
                    myField.focus();
                    sel = document.selection.createRange();
                    sel.text = myValue;
                } else if (myField.selectionStart || myField.selectionStart == '0') { //MOZILLA and others
                    var startPos = myField.selectionStart;
                    var endPos = myField.selectionEnd;
                    myField.value = myField.value.substring(0, startPos)
                        + myValue
                        + myField.value.substring(endPos, myField.value.length);
                } else {
                    myField.value += myValue;
                }
                calculateSMSs();
                $('#message').focus().keyup();
            });

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
                var smsType = getSMSType(content);
                $("#isunicode").val(smsType);

            }

            $("#message").on('keyup', function () {
                calculateSMSs();
            });

            $('#saveAsDraft').click(function() {
                $("#status").val('Draft');
                return true;
            });
            $('#SendSMSNow').click(function() {
                $("#status").val('Queue');
                return true;
            });

        });

    </script>
@endsection
