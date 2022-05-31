@extends($layout)

@section('pageVendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/vendors/summernote/summernote-bs4.min.css') }}">
@endsection

@section('pageCSS')
    <style>
        .recipient-error{
            border: 1px solid #ff7588 !important
        }
        .recipient-error:focus-visible{
            outline: 1px solid #ff7588 !important
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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('email.list') }}">Emails</a>
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
                                    <a class="nav-link active" id="base-sendEmail" data-toggle="tab" aria-controls="sendEmail" href="#sendEmail" role="tab" aria-selected="true">Send Email</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="base-groupEmail" data-toggle="tab" aria-controls="groupEmail" href="#groupEmail" role="tab" aria-selected="false">Send Group Email</a>
                                </li>
                            </ul>

                            <form class="form form-horizontal" method="post" action="{{ route('email.add.post') }}" enctype="multipart/form-data" id="sendEmailForm">
                                {{ csrf_field() }}
                                <input type="hidden" id="status" name="status" value="Queue" />
                                <div class="form-body px-1 pt-1">
                                    <div class="form-group row">
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
                                        <div class="col-12 input-element {{ $errors->has('subject') ? 'has-error' : '' }}">
                                            <input type="text" placeholder="Subject" class="selectdomain form-control block border-primary" id="subject" name="subject" value="{{ old('subject') }}">
                                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-12 input-element {{ $errors->has('from_email') ? 'has-error' : '' }}">
                                            <input type="text" placeholder="From Email" class="selectdomain form-control block border-primary" id="from_email" name="from_email" value="{{ old('from_email') }}">
                                            <span class="text-danger">{{ $errors->first('from_email') }}</span>
                                        </div>
                                    </div>

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="sendEmail" role="tabpanel" aria-labelledby="base-sendEmail">
                                            <div class="form-group row">
                                                <div class="col-12 input-element {{ $errors->has('recipient') ? 'has-error' : '' }}" id="recepientDiv">
                                                    <textarea placeholder="To Email List" id="recipient" name="recipient" class="block border-primary">{{old('recipient')}}</textarea>
                                                    <p style="font-size: 12px; font-weight: bold;" id="recipientErrMsg" class="text-danger pb-0 mb-0"></p>
                                                    <span style="font-size: 12px; font-weight: bold; color: #ff5b5b !important;" id="display_recipient_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span><br>
                                                    <input type="hidden" id="email_count" name="email_count" value="0"/>
                                                    <input type="hidden" id="recipientError" name="recipientError" value=""/>
                                                    <span class="text-danger">{{ $errors->first('recipient') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="groupEmail" role="tabpanel" aria-labelledby="base-groupEmail">
                                            <div class="form-group row">
                                                <div class="col-12 input-element {{ $errors->has('group_id') ? 'has-error' : '' }}">
                                                    <div class="form-group row" style="padding-right: 15px; padding-left: 15px;">
                                                        @foreach($Groups as $Group)
                                                            @php($countEmail = \App\Models\Contact::where('group_id', $Group->id)->whereNotNull('email')->where('status', 'Active')->count())
                                                            <div class="col-xl-4 col-md-3 custom-control custom-checkbox">
                                                                <input type="checkbox" value="{{ $Group->id }}" name="group_id[]" id="users-checkbox{{ $Group->id }}" onclick="{{ ($countEmail == 0)? 'alert(\'No Email In This Group\'); return false;':'return true;' }}" class="custom-control-input user-checkbox">
                                                                <label class="custom-control-label" for="users-checkbox{{ $Group->id }}">{{ $Group->name }} ({{ $countEmail }})</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <span class="text-danger">{{ $errors->first('group_id') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="message">Message</label>
                                        <div class="col-12 input-element {{ $errors->has('message') ? 'has-error' : '' }}">
                                            <textarea id="message" name="message" class="block border-primary" rows="4">{{old('message')}}</textarea>
                                            <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space, Enter or Tab to transliterate. <br>
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

                                    <input name="email_type" id="email_type" type="hidden" value="{{ empty(old('email_type'))? 'sendEmail':old('email_type')  }}">
                                </div>
                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-12 pl-2">
                                            <button type="submit" id="saveAsDraft" value="Draft" name="submit" class="btn btn-primary">
                                                <i class="fa fa-first-order"></i> Save As Draft
                                            </button>
                                            <button type="submit" id="sendEmailNow" value="Send" name="submit" class="btn btn-primary">
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
                                        <div class="p-2 bg-blue white media-body">
                                            <h3 class="text-bold-700">{{ $userBalance->email_balance }}</h3>
                                            <h5>Email Balance</h5>
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
                            <h4 class="card-title">Terms of Email</h4>
                        </div>
                        <hr>
                        <ul>
                            <li>Seprate Email by Comma. For example: test@email.com, test1@email.com</li>
                        </ul>
                    </div>
                </div>

                    <div class="card">
                        <div class="card-content collpase show">
                            <div class="card-header mb-1">
                                <h4 class="card-title">Preview Email</h4>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div id="previewEmail"></div>
                            </div>
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
    <script src="{{ asset('assets/app-assets/vendors/summernote/summernote-bs4.min.js') }}"></script>
@endsection

@section('pageJS')
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
                $('#message').avro({'bangla': false});
            });

            $('#tabMenu a[href="#{{ old('email_type') }}"]').tab('show');

            $('#base-sendEmail').on('click', function () {
                $('#email_type').val('sendEmail');
                $('input:checkbox').prop('checked',false);
            });

            $('#base-groupEmail').on('click', function () {
                $('#email_type').val('groupEmail');
                $('#recipient').val('').removeClass('recipient-error');
                $('#email_count').val(0);
                $('#display_recipient_count').text(0);
                $('#recipientErrMsg').text('');
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
                            $('#from_email').val(data.from_email);
                        }
                    });
                } else {
                    $('#from_email').val('');
                }
            });


            //Email Count Start
            $("#recipient").on('keyup', function (e) {
                var array = $.map(this.value.split(","), $.trim).join();
                this.value = $.map(this.value.split(","), $.trim).join(", ");
                var email= array.split(',')[array.split(',').length - 1];
                var pattern = /^[a-zA-Z_0-9.+\-]+@[a-zA-Z0-9_.+\-]+?\.[a-zA-Z]{2,3}$/;

                if(email.match(pattern) == null){
                    if (this.value != '') {
                        $('#recipient').addClass('recipient-error');
                        $('#recipientErrMsg').text('Email Not Correct');
                    } else {
                        $('#recipient').removeClass('recipient-error');
                        $('#recipientErrMsg').text('');
                    }

                } else {
                    $('#recipient').removeClass('recipient-error');
                    $('#recipientErrMsg').text('');

                }

                if (this.value != ''){
                    var words = this.value.match(/\S+/g).length;
                } else {
                    var words = 0;
                }


                $('#display_recipient_count').text(words);
                $("#email_count").val(words);
                if (words > 500) {
                    alert('Email must be smaller than 500 (number <= 500)');
                    var exacnumber = this.value.slice(0, 6499);
                    $('#display_recipient_count').text(500);
                    $("#email_count").val(500);

                }
            });

            $('#message').summernote({
                height: "200px",
                callbacks: {
                    onImageUpload: function(files) {
                        url = '{{ route('upload.image').'?_token='.csrf_token() }}'; //path is defined as data attribute for  textarea
                        sendFile(files[0], url, $(this));
                    },
                    onKeyup: function(e) {
                        setTimeout(function(){
                            $("#previewEmail").html($('#message').val());
                        },200);
                    }
                }
            });

            function sendFile(file, url, editor) {
                var data = new FormData();
                data.append("file", file);
                var request = new XMLHttpRequest();
                request.open('POST', url, true);
                request.onload = function() {
                    if (request.status >= 200 && request.status < 400) {
                        // Success!
                        var resp = $.parseJSON(request.responseText);
                        editor.summernote('insertImage', resp.url);
                    } else {
                        // We reached our target server, but it returned an error
                        var resp = request.responseText;
                    }
                };
                request.onerror = function(jqXHR, textStatus, errorThrown) {
                    // There was a connection error of some sort
                    console.log(jqXHR);
                };
                request.send(data);
            }

            $('#saveAsDraft').click(function() {
                $("#status").val('Draft');
                return true;
            });
            $('#sendEmailNow').click(function() {
                $("#status").val('Queue');
                return true;
            });
        });

    </script>
@endsection
