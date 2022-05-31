@extends('backend.layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="row">
        @if(empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4)
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-primary bg-darken-2">
                                <i class="feather icon-user-plus font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-primary white media-body">
                                <h5>Total Reseller</h5>
                                <h5 class="text-bold-400 mb-0">{{ $count_reseller }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Auth::user()->id_user_group != 4)
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-danger bg-darken-2">
                                <i class="feather icon-users font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-danger white media-body">
                                <h5>Total User</h5>
                                <h5 class="text-bold-400 mb-0">{{ $count_user }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(!empty(Auth::user()->reseller_id) && Auth::user()->id_user_group != 4)
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-blue bg-darken-2">
                                <i class="icon-wallet font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-warning white media-body">
                                <h5>Balance</h5>
                                <h5 class="text-bold-400 mb-0">{{ $reseller->available_balance }} BDT</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(!empty(Auth::user()->reseller_id) || Auth::user()->id_user_group == config('constants.USER_GROUP'))
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-success bg-darken-2">
                                <i class="icon-wallet font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-success white media-body">
                                <h5>Masking Balance</h5>
                                <h5 class="text-bold-400 mb-0">{{ $userWallet->masking_balance }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-success bg-darken-2">
                                <i class="icon-wallet font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-success white media-body">
                                <h5>Non Masking Balance</h5>
                                <h5 class="text-bold-400 mb-0">{{ $userWallet->non_masking_balance }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @if(config('constants.EMAIL_SERVICE'))
                <div class="col-xl-3 col-lg-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="media align-items-stretch">
                                <div class="p-2 text-center bg-success bg-darken-2">
                                    <i class="icon-wallet font-large-2 white"></i>
                                </div>
                                <div class="p-2 bg-gradient-x-success white media-body">
                                    <h5>Email Balance</h5>
                                    <h5 class="text-bold-400 mb-0">{{ $userWallet->email_balance }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
        @endif
    </div>

    <div class="row match-height">
        <div class="col-md-6">
            <div class="card" id="request-status"  data-url={{route('dashboard.channel_status')}} >
                <div class="card-header">
                    <h4 class="card-title">SMS Request Summary (Last 90 days)</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 ps-container ps-theme-default">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>User</th>
                                    <th>API</th>
                                    <th>WEB</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="sms-status-report" data-url={{route("dashboard.outbox_status")}}  class="card">
                <div class="card-header">
                    <h4 class="card-title">SMS Status Reports (Last 90 days)</h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 ps-container ps-theme-default">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>User</th>
                                    <th>Queued</th>
                                    <th>Processing</th>
                                    <th>Sent</th>
                                    <th>Failed</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/Recent Orders & Monthly Salse -->
    <!-- Social & Weather -->

@endsection

@section('pageJS')
    <script src="{{ asset('assets/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>

    <script src="{{ asset('assets/app-assets/js/scripts/modal/components-modal.js') }}"></script>
    <script>
        // Get the modal


        function StringFormat(source, params) {
            $.each(params,function (i, n) {
                source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
            })
            return source;
        }

        $(document).ready(function (){
            $.fn.loadRequestSummary = function () {
                var card_url = $(this).data()['url'];
                var _that = this;
                var tbody_html = '';
                $.getJSON(card_url, function(res) {
                    $.each(res, function(idx, status_info) {
                        var row_html = StringFormat("<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td></tr>", [idx+1, status_info.username, status_info.API, status_info.WEB, status_info.total]);
                        tbody_html += row_html;
                    });
                    $(_that).find('table >tbody').html(tbody_html);
                });
            }

            $('#request-status').loadRequestSummary();

            $.fn.loadSMSSummary = function () {
                var card_url = $(this).data()['url'];
                var _that = this;
                var tbody_html = '';
                $.getJSON(card_url, function(res) {
                    $.each(res, function(idx, status_info) {
                        var row_html = StringFormat("<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td></tr>",
                        [idx+1, status_info.username, status_info.Queue, status_info.Processing, status_info.Sent, status_info.Failed, status_info.total]);
                        tbody_html += row_html;
                    });
                    $(_that).find('table >tbody').html(tbody_html);
                });
            }

            $('#sms-status-report').loadSMSSummary();





        });
    </script>
@endsection
