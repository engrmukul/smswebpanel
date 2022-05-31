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
                        <div class="card-body">

                            <ul class="nav nav-tabs nav-top-border no-hover-bg" id="tabMenu" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="base-sendSms" data-toggle="tab" aria-controls="sendSms" href="#sendSms" role="tab" aria-selected="true">Send SMS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="base-groupSms" data-toggle="tab" aria-controls="groupSms" href="#groupSms" role="tab" aria-selected="false">Send Group SMS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="base-fileSms" data-toggle="tab" aria-controls="fileSms" href="#fileSms" role="tab" aria-selected="false">Upload File</a>
                                </li>
                            </ul>

                            <form class="form form-horizontal" method="post" action="{{ route('message.add.post') }}" enctype="multipart/form-data">
{{--                                {{ csrf_field() }}--}}
                                @csrf
                                <input type="hidden" id="status" name="status" value="Queue" />
                                <div class="form-body px-1 pt-1">

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="mask">SenderID</label>
                                        <div class="col-12 input-element {{ $errors->has('mask') ? 'has-error' : '' }}">
                                            <select class="selectmask form-control custom-select block border-primary" id="mask" name="mask">
                                                <option value="">Select SenderID</option>
                                                @foreach($senderIds as $senderId)
                                                    @if(!is_numeric($senderId->senderID))
                                                        <option value="{{ $senderId->senderID }}" {{ (old('mask') == $senderId->senderID)? 'selected': '' }}>{{ $senderId->senderID }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('mask') }}</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="is_mask" value="mask">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="sendSms" role="tabpanel" aria-labelledby="base-sendSms">
                                            <div class="form-group row">
                                                <label class="col-12 col-form-label" for="number">Recipient Number</label>
                                                <div class="col-12 input-element {{ $errors->has('number') ? 'has-error' : '' }}">
                                                    <textarea id="number" name="number" class="block border-primary">{{old('number')}}</textarea>
                                                    <span style="font-size: 12px; font-weight: bold;color: #ff5b5b !important;" id="display_number_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span><br>
                                                    <span class="text-danger">{{ $errors->first('number') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="groupSms" role="tabpanel" aria-labelledby="base-groupSms">
                                            <div class="form-group row">
                                                <label class="col-12 col-form-label" for="mask">Phone Group</label>
                                                <div class="col-12 input-element {{ $errors->has('group_id') ? 'has-error' : '' }}">
                                                    <div class="form-group row" style="padding-right: 15px; padding-left: 15px;">
                                                        @foreach($phoneGroups as $phoneGroup)
                                                            @php($countNumber = \App\Models\Contact::where('group_id', $phoneGroup->id)->where('status', 'Active')->count())
                                                            <div class="col-xl-4 col-md-3 custom-control custom-checkbox">
                                                                <input type="checkbox" value="{{ $phoneGroup->id }}" name="group_id[]" id="users-checkbox{{ $phoneGroup->id }}" onclick="{{ ($countNumber == 0)? 'alert(\'No Number In This Group\'); return false;':'return true;' }}" class="custom-control-input user-checkbox">
                                                                <label class="custom-control-label" for="users-checkbox{{ $phoneGroup->id }}">{{ $phoneGroup->name }} ({{ $countNumber }})</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <span class="text-danger">{{ $errors->first('group_id') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="fileSms" role="tabpanel" aria-labelledby="base-fileSms">
                                            <div class="form-group row">
                                                <label class="col-12 col-form-label">Number File</label>
                                                <div class="col-12 input-element {{ $errors->has('file') ? 'has-error' : '' }}">
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="form-control border-primary custom-file-input" name="file" id="inputGroupFile01"  accept=".csv, .xls, .xlsx">
                                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                        </div>
                                                        <button type="button" class="btn btn-danger" id="fileClear"> Remove</button>
                                                    </div>
                                                    <input type="hidden" id="number_count" name="number_count" value="0"/>
                                                    <span style="font-size: 12px; font-weight: bold; color: #ff5b5b !important;" id="display_file_number_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span><br>
                                                    <span style="color: #ff5b5b !important;">File Type xlsx, xls, or CSV File. Example File:
                                                        <span class="text-primary">
                                                            <a href="{{ asset('assets/example/number-example-file.csv') }}">CSV </a> |
                                                            <a href="{{ asset('assets/example/number-example-file.xls') }}"> XLS/XLSX</a>
                                                        </span>
                                                    </span><br>
                                                    <span class="text-danger">{{ $errors->first('file') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="mask">Template</label>
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
                                            <textarea id="message" name="message" class="block border-primary" rows="4">{{old('message')}}</textarea>
                                            <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space, Enter or Tab to transliterate. <br>
                                            <span>Entered Char : <span id="countBox2" style="color: #ff5b5b !important;" class="text-bold-700"></span>, Number of char per SMS : <span id="actualSMSLength" class="text-primary text-bold-700"></span> ,Total SMS :<span style="color: #ff5b5b !important;" id="usrSMSCnt" class="text-bold-700"></span></span><br>
                                            <input type="hidden" name="page" value="" id="page"/>
                                            <input type="hidden" name="isunicode" value="" id="isunicode"/>
                                            <input type="hidden" name="smscharlength" value="" id="smscharlength"/>
                                            <input type="hidden" name="totalsmscount" value="" id="totalsmscount"/>
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

                                    <input name="sms_type" id="type_sms" type="hidden" value="{{ empty(old('sms_type'))? 'sendSms':old('sms_type')  }}">
                                </div>
                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-12 pl-2">
                                            <button type="submit" id="saveAsDraft" value="Draft" name="submit" class="btn btn-primary">
                                                <i class="fa fa-first-order"></i> Save As Draft
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="SendSMSNow">
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
                            <div class="col-xl-6 col-lg-6 col-12">
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
                        <div class="col-xl-6 col-lg-6 col-12">
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
        <div class="row">

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

            {{--$('#tabMenu a[href="#{{ old('sms_type') }}"]').tab('show');--}}

            $('#fileClear').on('click', function () {
                $('.custom-file-label').html('Choose file');
                $('#inputGroupFile01').val(null);
            });
            $('.custom-file input').change(function (e) {
                $(this).next('.custom-file-label').html(e.target.files[0].name);
            });

            $('#base-sendSms').on('click', function () {
                $('#type_sms').val('sendSms');
                $('.custom-file-label').html('Choose file');
                $('#inputGroupFile01').val(null);
                $('#display_file_number_count').text(0);
                $('input:checkbox').prop('checked',false);
            });

            $('#base-groupSms').on('click', function () {
                $('#type_sms').val('groupSms');
                $('.custom-file-label').html('Choose file');
                $('#number').val('');
                $('#number_count').val(0);
                $('#display_number_count').text(0);
                $('#inputGroupFile01').val(null);
                $('#display_file_number_count').text(0);
            });

            $('#base-fileSms').on('click', function () {
                $('#type_sms').val('fileSms');
                $('#number').val('');
                $('#number_count').val(0);
                $('#display_number_count').text(0);
                $('input:checkbox').prop('checked',false);
            });

            $("#template").on("change", function () {
                var template_msg = $(this).val();
                $("#message").val(template_msg);
                calculateSMSs();
            });

            //Number Count Start


            $('#inputGroupFile01').change(function (event) {
                /*Checks whether the browser supports HTML5*/
                if (typeof (FileReader) != "undefined") {
                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
                    /*Checks whether the file is a valid excel file*/
                    if (regex.test($(this).val().toLowerCase())) {
                        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                        if ($("#inputGroupFile01").val().toLowerCase().indexOf(".xlsx") > 0) {
                            xlsxflag = true;
                        }

                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var data = e.target.result;
                            /*Converts the excel data in to object*/
                            if (xlsxflag) {
                                var workbook = XLSX.read(data, {type: 'binary'});
                            } else {
                                var workbook = XLS.read(data, {type: 'binary'});
                            }
                            /*Gets all the sheetnames of excel in to a variable*/
                            var sheet_name_list = workbook.SheetNames;

                            var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
                            sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/
                                /*Convert the cell value to Json*/
                                if (xlsxflag) {
                                    var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                                } else {
                                    var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                                }
                                $("#display_file_number_count").text(exceljson.length);
                                $('#number_count').val(exceljson.length);

                            });
                        }
                        if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                            reader.readAsArrayBuffer($("#inputGroupFile01")[0].files[0]);
                        } else {
                            reader.readAsBinaryString($("#inputGroupFile01")[0].files[0]);
                        }

                    } else {
                        var f = event.target.files[0];
                        if (f) {
                            var r = new FileReader();
                            r.onload = function (e) {
                                var contents = e.target.result;
                                var rowsn = contents.match(/(?:"(?:[^"]|"")*"|[^,\n]*)(?:,(?:"(?:[^"]|"")*"|[^,\n]*))*\n/g).length;
                                $("#display_file_number_count").text(rowsn - 1);
                                $('#number_count').val(rowsn - 1);
                            }
                            r.readAsText(f);
                        }
                    }
                } else {
                    alert("Sorry! Your browser does not support HTML5!");
                }
            });

            $("#number").on('keyup', function () {
                this.value = $.map(this.value.split(","), $.trim).join(", ");
                var words = this.value.match(/\S+/g).length;
                $('#display_number_count').text(words);
                $("#number_count").val(words);
                if (words > 500) {
                    alert('Phone number must be smaller than 500 (number <= 500)');
                    var exacnumber = this.value.slice(0, 6499);
                    $('#display_number_count').text(500);
                    $("#recipientList").val(exacnumber);
                    $("#number_count").val(500);

                }
            });
            //Number Count End


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
