@extends($layout)
@section('pageVendorCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/app-assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
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
                    {{--                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('invoice.list') }}">Route List</a>--}}
                    {{--                    </li>--}}
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section id="configuration">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Select User and Time</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <form role="form" id="reportGet" method="POST">
                                @csrf
                                <div class="box-body">
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

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="invoice_from">Invoice From</label>
                                        <div class="col-12 input-element {{ $errors->has('invoice_from') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <input type="text" autocomplete="off" id="invoice_from" class="form-control border-primary" value="{{old('invoice_from')}}" name="invoice_from" placeholder="yyyy-mm-dd">
                                                <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('invoice_from') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-form-label" for="invoice_to">Invoice To</label>
                                        <div class="col-12 input-element {{ $errors->has('invoice_to') ? 'has-error' : '' }}">
                                            <div class="input-group">
                                                <input type="text" autocomplete="off" id="invoice_to" class="form-control border-primary" value="{{old('invoice_to')}}" name="invoice_to" placeholder="yyyy-mm-dd">
                                                <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <span class="text-danger">{{ $errors->first('invoice_to') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" id="showInvoice" class="btn btn-secondary btn-block">Show Invoice</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
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
                        <form class="form" method="post" action="{{ route('invoice.create.post') }}">
                            @csrf
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered sourced-data" width="100%">
                                    <thead>
                                    <tr>
                                        @foreach($tableHeaders as $key=>$tableHeader)
                                            <th class="text-center">{{ $tableHeader }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($id = 0)
                                    @php($total = 0)
                                    @foreach($datas as $data)
                                        <?php
                                        if ($data->operator_prefix == '17' || $data->operator_prefix == '13') {
                                            $operator = 'GrameenPhone';
                                        } elseif ($data->operator_prefix == '19' || $data->operator_prefix == '14') {
                                            $operator = 'BanglaLink';
                                        } elseif ($data->operator_prefix == '16') {
                                            $operator = 'Airtel';
                                        } elseif ($data->operator_prefix == '18') {
                                            $operator = 'Robi';
                                        } else {
                                            $operator = 'Teletalk';
                                        }
                                        $price = ($data->mask != null)?$sms_rate->selling_masking_rate:$sms_rate->selling_nonmasking_rate;
                                        $total += $data->total*$price;
                                        ?>
                                        <tr>
                                            <td>{{ $id+1 }}</td>
                                            <td>{{ $operator }}</td>
                                            <td>{{ ($data->mask != null)?'Masking':'Non Masking' }}</td>
                                            <td>{{ $data->total }}</td>
                                            <td>{{ $price }}</td>
                                            <td class="text-right">{{ number_format((float)($data->total*$price), 2, '.', '') }}</td>
                                        </tr>
                                        <input type="hidden" name="operator[]" class="form-control item_sl" value="{{ $operator }}"/>
                                        <input type="hidden" name="sms_type[]" class="form-control item_sl" value="{{ ($data->mask != null)?'Masking':'Non Masking' }}"/>
                                        <input type="hidden" name="total_sms[]" class="form-control item_sl" value="{{ $data->total }}"/>
                                        <input type="hidden" name="default_price[]" class="form-control item_sl" value="{{ $price }}"/>
                                        @php($id++)
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <h4 class="card-title text-right mr-2">TOTAL : {{ number_format((float)$total, 2, '.', '') }}</h4>
                            <input type="hidden" name="total_price" class="form-control item_sl" value="{{ $total }}"/>
                            <input type="hidden" name="user_id" class="form-control item_sl" value="{{ $userId }}"/>
                            <input type="hidden" name="reseller_id" class="form-control item_sl" value="{{ $resellerId }}"/>
                            <input type="hidden" name="invoice_from" class="form-control item_sl" value="{{ $startDate }}"/>
                            <input type="hidden" name="invoice_to" class="form-control item_sl" value="{{ $endDate }}"/>
                            <input type="hidden" name="total_row" class="form-control item_sl" value="{{ $id }}"/>
                            @if($id > 0)
                            <div class="box-footer">
                                <button type="submit" class="btn btn-secondary btn-block">Create Invoice</button>
                            </div>
                            @endif
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageVendorJS')
    <script src="{{ asset('assets/app-assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
@endsection


@section('pageJS')
    {{--    @include('backend.layouts.datatable-dynamic')--}}
    <script>
        $(document).ready(function () {
            $('#invoice_from').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd',
                // startDate:  new Date(),
                // endDate: new Date(new Date().setDate(new Date().getDate() + 42)),
                // minDate:  new Date(),
                // maxDate: new Date(new Date().setDate(new Date().getDate() + 42))
            });


            $('#invoice_to').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd',
                // startDate:  new Date(),
                // endDate: new Date(new Date().setDate(new Date().getDate() + 42)),
                // minDate:  new Date(),
                // maxDate: new Date(new Date().setDate(new Date().getDate() + 42))
            });


            $("#user").select2({
                ajax: {
                    url: '{{ route('select.invoice.user') }}',
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

            $("#reseller").select2({
                ajax: {
                    url: '{{ route('select.reseller') }}',
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

            $("#reseller").change(function () {
                $('#user').val('').trigger('change.select2');
            });

            $("#user").change(function () {
                $('#reseller').val('').trigger('change.select2');
            });
        });
    </script>
@endsection
