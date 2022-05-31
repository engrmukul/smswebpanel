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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Select Time</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <form role="form" id="reportGet">
                                <div class="form-body">
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
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Reseller Name</label>
                                        <div class="col-md-9  input-element {{ $errors->has('reseller') ? 'has-error' : '' }}">
                                            <select class="select2 form-control block" id="reseller" name="reseller">
                                                <option value="">Select Reseller Name</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('reseller') }}</span>
                                        </div>
                                    </div>
                                    @endif

                                    @if (Auth::user()->id_user_group != 4)
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">User Name</label>
                                            <div class="col-md-9 input-element {{ $errors->has('user') ? 'has-error' : '' }}">
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
                                    <button type="submit" class="btn btn-secondary btn-block">Show Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
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
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered sourced-data text-center">
                                    <thead>
                                    <tr>
                                        @foreach($tableHeaders as $key=>$tableHeader)
                                            <th>{{ $tableHeader }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                </table>
                            </div>
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
            var table = $('#datatable').DataTable({
                serverSide: true,
                dom: 'Bfrtip',
                language: {
                    processing: "<img width='75px' src='{{ asset('assets/images/loading.gif') }}'>",
                },
                columns: [
                        @foreach($tableHeaders as $key=>$tableHeader)
                    {data: "{{$key}}"},
                    @endforeach
                ],
                searching:false,
                pageLength: 20,
                lengthMenu: [[20, 30, 50, -1], [20, 30, 50, "All"]],
                buttons: ['pageLength',
                    {
                        extend: 'print',
                        text: 'Print',
                        autoPrint: true,
                        exportOptions: {
                            columns: [':not(.hidden-print)'],
                            modifier: {
                                page: 'all'
                            }
                        },

                        customize: function (win) {

                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('table')
                                .removeClass('table-striped table-responsive-sm table-responsive-lg dataTable table-responsive-xl table-responsive')
                                .addClass('compact')
                                .css('font-size', 'inherit', 'color', '#000');

                        }
                    }
                ],
                ajax: "{{ $ajaxUrl }}",
            });

            var frm = $('#reportGet');
            frm.submit(function (e) {
                e.preventDefault();
                var url = frm.serialize()
                table.ajax.url('{{ $ajaxUrl }}?' + url).load();
            });
            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel, .buttons-page-length').addClass('btn mb-2');
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
    </script>
@endsection
