@extends($layout)
@section('title')
    View Invoice
@endsection

@section('content')
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="clearfix">
                                <div class="pull-left row">
                                    @if(!empty($config->logo) && file_exists("assets/images/".$config->logo))
                                        <img src="{{ asset("assets/images/".$config->logo) }}" width="70px" height="70px">
                                        <span><h4><strong>{{ $config->brand_name }}</strong></h4><span><strong>Internet Service Provider</strong></span></span>
                                    @else
                                        <span><span class="logo">{{ $config->brand_name }}</span><br><span><strong>Internet Service Provider</strong></span></span>
                                    @endif
                                </div>
                                <div class="pull-right text-right">
                                    <h4>Invoice #<br>
                                        <strong>CINV-{{sprintf('%06d',$invoice->id)}}</strong>
                                    </h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="pull-left m-t-5 table-borderless table-sm">
                                        <address>
                                            <table class="table">
                                                <tr>
                                                    <td>{{ $invoice->user_id? 'Client' : 'Reseller' }} Name:</td>
                                                    <th>{{ $invoice->user_id? $invoice->user->name : $invoice->reseller->reseller_name }}</th>
                                                </tr>
                                                <tr>
                                                    <td>Address:</td>
                                                    <th>

                                                        {{ $invoice->user_id? $invoice->user->address : $invoice->reseller->address }}
                                                        <br>
                                                        Bangladesh
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Phone:</td>
                                                    <th>{{ $invoice->user_id? $invoice->user->mobile : $invoice->reselller->phone }}</th>
                                                </tr>
                                            </table>
                                        </address>
                                    </div>
                                    <div class="pull-right m-t-5 text-right">
                                        <p>Invoice Date: <strong>{{ $invoice->created_at->format('d M Y') }}</strong></p>
                                        <strong>{!! $config->address !!}<br>Bangladesh</strong><br>
                                        Phone: <strong>{{ $config->phone }}</strong><br>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive table-sm">
                                        <table class="table table-bordered m-t-5">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Operator</th>
                                                <th>SMS Type</th>
                                                <th>Total SMS</th>
                                                <th>Default Price</th>
                                                <th>Total Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php($datas = json_decode($invoice->invoice_details))
                                            @php($id = 1)
                                            @foreach($datas as $data)
                                                <tr>
                                                    <td>{{ $id }}</td>
                                                    <td>{{ $data->operator }}</td>
                                                    <td>{{ $data->sms_type }}</td>
                                                    <td>{{ $data->total_sms }}</td>
                                                    <td>{{ $data->default_price }}</td>
                                                    <td class="text-right">{{ number_format($data->total_sms*$data->default_price,2,'.',',') }}</td>
                                                </tr>
                                                @php($id++)
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-6">
                                    <div class="clearfix">
                                        {{--                                In Word : <strong>{{ ucwords($number_word->format($invoice->total_price)) }} Only</strong>--}}
                                        <h5>PAYMENT TERMS AND POLICIES</h5>

                                        <small>
                                            All accounts are to be paid in date 7 of Month To be paid by Cash or cheque
                                            or Bank Deposit or direct payment online. If account is not paid in date 7
                                            of Month, the account will stop.
                                        </small>
                                    </div>
                                </div>
                                <div class="offset-xl-3 col-xl-3 col-6 text-right">
                                    <div class="table-borderless table-sm">
                                        <table class="table">
                                            <tr>
                                                <td class="text-right"><b>Total:</b></td>
                                                <td class="text-right">
                                                    {{ $invoice->total }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-6">
                                    <br><br>
                                    _________________________________ <br>
                                    <strong>Customer Signature</strong> <br>

                                </div>

                                <div class="col-xl-3 col-6 offset-xl-3 text-right">
                                    <br><br>
                                    __________________________________ <br>
                                    <strong>Authorized Signature & Co. Stamp</strong> <br>
                                </div>
                            </div>
                            <hr>
                            <div class="d-print-none">
                                <div class="pull-right">
                                    <a href="javascript:window.print()" class="btn btn-light waves-effect waves-light"><i
                                            class="fa fa-print"></i></a>
                                    <a href="{{ route('invoice.list') }}"
                                       class="btn btn-primary waves-effect waves-light">Back</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>                    <!-- end row -->
                    </div>
                </div> <!-- end card -->
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </section>
@endsection
