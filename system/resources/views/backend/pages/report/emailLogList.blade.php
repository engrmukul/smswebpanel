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
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section id="configuration">
        <div class="row">
            <div class="col-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Select Time</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <form role="form" id="reportGet">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="report_date" value="today">
                                                Today Report
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="report_date" value="yeasterday">
                                                Yesterday Report
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="report_date" value="last_seven_days">
                                                Last 7 Days Report
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="report_date" value="last_thirteen_days">
                                                Last 30 Days Report
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="report_date" value="this_month">
                                                This Month Report
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="report_date" value="last_month">
                                                Last Month Report
                                            </label>
                                        </div>

                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="report_date" value="last_ninety_days">
                                                Last 90 Days
                                            </label>
                                        </div>
                                    </div>

                                    @if (Auth::user()->id_user_group != 4 && empty(Auth::user()->reseller_id))
                                        <div class="form-group">
                                            <label>Reseller Name</label>
                                            <div class="  input-element {{ $errors->has('reseller') ? 'has-error' : '' }}">
                                                <select class="select2 form-control block" id="reseller" name="reseller">
                                                    <option value="">Select Reseller Name</option>
                                                </select>
                                                <span class="text-danger">{{ $errors->first('reseller') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if (Auth::user()->id_user_group != 4)
                                        <div class="form-group">
                                            <label>User Name</label>
                                            <div class="  input-element {{ $errors->has('user') ? 'has-error' : '' }}">
                                                <select class="select2 form-control block" id="user" name="user">
                                                    <option value="">Select User Name</option>
                                                </select>
                                                <span class="text-danger">{{ $errors->first('user') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-secondary btn-block">Show Logs</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
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
                            @include('backend.pages.elements.tableboard')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageJS')
    @include('backend.layouts.datatable-dynamic_without_button')
    <script>
        $(document).ready(function () {
            $("#user").select2({
                ajax:{
                    url: '{{ route('select.user') }}',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return{
                            searchTerm : params.term,
                            _token : '{{ csrf_token() }}'
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

            $("#reseller").select2({
                ajax:{
                    url: '{{ route('select.reseller') }}',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return{
                            searchTerm : params.term,
                            _token : '{{ csrf_token() }}'
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

            $("#reseller").change(function () {
                $('#user').val('').trigger('change.select2');
            });

            $("#user").change(function () {
                $('#reseller').val('').trigger('change.select2');
            });
        });
        function decodeHtml(str)
        {
            var map =
                {
                    '&amp;': '&',
                    '&lt;': '<',
                    '&gt;': '>',
                    '&quot;': '"',
                    '&#039;': "'"
                };
            if (str == null || Number.isInteger(str)){
                return str;
            } else {
                return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
            }
        }
    </script>


@endsection
